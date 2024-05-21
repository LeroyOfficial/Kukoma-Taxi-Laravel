const API_ENDPOINT = 'http://localhost/kukoma-taxi/public/user/'

const MAPBOX_API_KEY = 'pk.eyJ1IjoibGVyb3ktb2ZmaWNpYWwiLCJhIjoiY2xuOHVrczY3MDFtZzJrcWdyZ2E2ejdnaiJ9.ye5kWKO5CVGRoBLVGVtBKg'

const MAPBOX_DIRECTION_API_ENDPOINT ="https://api.mapbox.com/directions/v5/mapbox/driving/"

let currentLocation = {
    long: null,
    lat: null,
};

let fromCoordinates = {
    long: null,
    lat: null,
};

let toCoordinates = {
    long: null,
    lat: null,
};

let directionData = null;

let distance = 0;

let duration = 0;



$(document).ready(function() {

    function getUserLocation() {
        if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            // Use the lat and long here (e.g., display on map, send to server)
            console.log(`Latitude: ${latitude}, Longitude: ${longitude}`);

            currentLocation.lat = latitude;
            currentLocation.long = longitude;

            fromCoordinates.lat = latitude;
            fromCoordinates.long = longitude;

            getUserAdressName();

            $('#pickup').val((`{"name":"Current Location", "lat": ${latitude},"long": ${longitude}}`));


                const coordinates = {
                    lat: latitude,
                    long: longitude,
                }

                addMarker('userLocation',coordinates, 'red')
                flyTo(coordinates);

            },
            (error) => {
            handleError(error);
            }
        );
        } else {
        console.error("Geolocation is not supported by this browser.");
        }
    }

    function handleError(error) {
    switch (error.code) {
        case error.PERMISSION_DENIED:
        console.error("User denied the request for Geolocation.");
        break;
        case error.POSITION_UNAVAILABLE:
        console.error("Location information is unavailable.");
        break;
        case error.TIMEOUT:
        console.error("The request to get user location timed out.");
        break;
        default:
        console.error("An unknown error occurred.");
    }
    }

    function getUserAdressName() {
        var url = 'https://api.geoapify.com/v1/geocode/reverse?lat='+currentLocation.lat+'&lon='+currentLocation.long+'&apiKey=20d91e693d5640f69ad8139985dffc7a';

        $.ajax({
            url: url,
            dataType: 'json',
            success: function(data) {
                const locationName = data.features[0].properties.name;

                console.log('current address ' + locationName);

                $('.from-span').text('Current Location ('+locationName+')');

                $('#pickup').val((`{"name":"${locationName}", "lat": ${currentLocation.lat},"long": ${currentLocation.long}}`));

            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error during AJAX request:', textStatus, errorThrown);
            }
        });
    }

    getUserLocation();

    getUserLocation();

    function getDirections() {
        if(!fromCoordinates.lat || !fromCoordinates.long)
            {
                getUserLocation();
                return;
            }

        const url = MAPBOX_DIRECTION_API_ENDPOINT+
            fromCoordinates?.long+","+fromCoordinates?.lat+";"+
            toCoordinates?.long+","+toCoordinates?.lat+
            "?overview=full&geometries=geojson"+
            "&access_token="+MAPBOX_API_KEY;

        $.ajax({
            url: url,
            dataType: 'json',
            success: function(data) {
                if (data.code === 'Ok') {
                    const route = data.routes[0];
                    console.log('route data = ' + route);

                    if(route.geometry.coordinates)
                        {
                            directionData = route.geometry.coordinates;
                            console.log('trip route directions are', directionData)
                            addRouteToMap(directionData);
                        }

                    const distance_result = route.distance*0.000621371392;

                    console.log('distance is ',distance_result);

                    distance = distance_result.toFixed(1);
                    $('#distance-span').text(distance);
                    $('#distance-input').val(distance);


                    const duration_result = route.duration/60;
                    console.log('duration is ', duration_result);

                    duration = duration_result.toFixed(0);
                    $('#duration-span').text(duration);
                    $('#duration-input').val(duration);

                    $('#distance-duration-div').removeClass('d-none');

                    $('#confirm-address-btn-div').addClass('d-none');
                    $('#select-car-div').removeClass('d-none');

                } else {
                    alert("Error retrieving directions: ", "make sure you have an internet connection which is needed to calculate the trip dustance and get trip direction... IT'S IMPOSSIBLE TO DO THIS OFFLINE");
                    console.error('Error retrieving directions:', data.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error retrieving directions: ", "make sure you have an internet connection which is needed to calculate the trip dustance and get trip direction... IT'S IMPOSSIBLE TO DO THIS OFFLINE");
                console.error('Error during AJAX request:', textStatus, errorThrown);
            }
        });
    }

    function addRouteToMap(route) {
        // map.addSource('some id', {
        //     type: 'geojson',
        //     data: {
        //         "type": "FeatureCollection",
        //         "features": [{
        //             "type": "Feature"s,
        //             "properties": {},
        //             "geometry": {
        //                 "type": "Line",
        //                 "coordinates": route
        //             }
        //         }]
        //     }
        // });

        map.addSource('route', {
            'type': 'geojson',
            'data': {
                'type': 'Feature',
                'properties': {},
                'geometry': {
                    'type': 'LineString',
                    'coordinates': route
                }
            }
        });

        map.addLayer({
            'id': 'route',
            'type': 'line',
            'source': 'route',
            'layout': {
                'line-join': 'round',
                'line-cap': 'round'
            },
            'paint': {
                'line-color': 'yellow',
                'line-width': 4
            }
        });

    }

    function fetchAddresses(searchTerm) {
        if(searchTerm.lenght == 0 || searchTerm.length < 3)
            {
                $('#search-result').addClass('d-none');
                return;
            }

        $.ajax({
        url: API_ENDPOINT+"booking/fetch-address",
        type: "GET",
        data: { name: searchTerm },
        dataType: "json",
        beforeSend: function() {
            $('#search-result').removeClass('d-none');
            $("#search-result").html("Searching..."); // Display a loading message
        },
        success: function(response) {
            if(response.success)
                {
                    $('#search-result').removeClass('d-none');
                    if(response.results)
                        {
                            let results = response.results;
                            const resultsHtml = results.map(address => `
                                <button class='btn btn-main address-name cursor-pointer mb-1' type="button">
                                    <span class='name'>${address.name}</span>
                                    <span class='d-none latitude'>${address.latitude}</span>
                                    <span class='d-none longitude'>${address.longitude}</span>
                                </button>
                            `).join("");

                            $("#search-result").html(resultsHtml);
                        }
                    else
                        {
                            $("#search-result").html(`${response.message}`);
                        }
                }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error fetching results:", textStatus, errorThrown);
            $("#search-result").html("An error occurred while searching.");
        }
        });
    }

    $("#destination").on("input", function() {
        const searchTerm = $(this).val();

        fetchAddresses(searchTerm);
    });

    function chooseAddress(name, longitude, latitude) {
        $('#destination').val(name);

        $('#to-span').text(name);

        $('#destination-cords').val(latitude);
        toCoordinates.lat = latitude;
        toCoordinates.long = longitude;

        const coordinates = {
            lat: latitude,
            long: longitude,
        }

        addMarker('destination', coordinates, 'green');
        flyTo(coordinates);

        $('#search-result').addClass('d-none');
        $('#confirm-address-btn-div').removeClass('d-none');
    }

    $("#search-result").on("click", ".address-name", function() {
        const name = $(this).find('.name').text();
        const latitude = $(this).find('.latitude').text();
        const longitude = $(this).find('.longitude').text();

        chooseAddress(name, longitude, latitude);
    })

    $('#confirm-address-btn').click(function() {
        getDirections();
    })

    function selectCar(car) {
        $('#car-type-not-selected').addClass('d-none');

        $('#next-btn-div').removeClass('d-none');


        $('.car-btn').removeClass('selected');
        car.addClass('selected');

        var carTypeName = car.find('.car-type-name').text();

        $('#car-type-name-input').val(carTypeName);
        console.log('#car-type-name-input ' , $('#car-type-name-input').val(carTypeName));

        var carTypePrice = Number(car.find('.car-type-price').text());

        var final_price = carTypePrice * distance;

        console.log('final price is ' + final_price);

        $('#car-type-price-input').val(final_price);
        console.log('#car-type-price-input is ', $('#car-type-price-input').val(final_price));

        $('#price-input').val(final_price);
        console.log('#price-input ' + $('#price-input').val(final_price));

        $('.price-span-value').text(final_price.toLocaleString());
        console.log('.price-span-value ', $('.price-span-value').text(final_price.toLocaleString()));

        $('#price-div').removeClass('d-none');
    }

    $('.car-btn').click(function() {
        selectCar($(this));
    });

    $('#directions .next-btn').click(function() {
    if(!$('#closest_driver_id-input').val())
        {
            fetchAvailableDrivers();
            // alert("Please select a Car Type");
        }

    if(!$('#car-type-price-input').val())
        {
            $('#car-type-not-selected').removeClass('d-none');
            alert("Please select a Car Type");
        return;
        }

    $('#directions').addClass('d-none');
    $('#payment').removeClass('d-none');
    });

    $('#payment .back-btn').click(function() {
    $('#directions').removeClass('d-none');
    $('#payment').addClass('d-none');
    });

    $('#mobile-label').click(function() {
    $('#mobile-div').removeClass('d-none');
    $('#visa-div').addClass('d-none');
    });

    $('#visa-label').click(function() {
    $('#visa-div').removeClass('d-none');
    $('#mobile-div').addClass('d-none');
    });

    function fetchAvailableDrivers() {
        $.ajax({
            url: API_ENDPOINT+"booking/fetch-available-drivers",
            type: "GET",
            dataType: "json",
            beforeSend: function() {
                //
            },
            success: function(data) {
                if(data.success)
                    {
                        if(data.drivers)
                            {
                                let drivers = data.drivers;
                                console.log('Driver fetch res = ' + drivers[0].first_name +' '+ drivers[0].last_name +' '+ drivers[0].current_location);
                                console.log('Current location parse ', JSON.parse(drivers[0].current_location).lat)
                                calculateNearestDriver (drivers);
                            }
                        else
                            {
                                alert('no drivers available right now... try again later');
                                console.log('response = ', data.message, 'admin: make sure drivers are online')
                            }
                    }
                else
                    {
                        alert('failed to fetch available drivers');
                        console.log('failed to fetch available drivers');
                    }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error fetching drivers:", textStatus, errorThrown);
            }
            });
    }

    async function calculateNearestDriver (driverList) {
        let available_driver_list = driverList;
        let old_distance = 0;
        let shortest_distance = 0;
        let closest_driver = null;

        console.log('');

        // {"lat":-15.8069,"long":35.0267,"address":"null","last_update":1700406910098}
        // {"lat":-15.7902,"long":35.0023,"address":"null","last_update":2024-03-26 22:49:44}

        const fetch_distance = async (driver) => {
            // if(!currentLocation.lat)
            //     {
            //         getUserLocation();
            //     }

            console.log('current driver is ', driver);

            console.log('driver coords', JSON.parse(driver?.current_location));

            const driver_coords = JSON.parse(driver?.current_location);
            const driver_lat = driver_coords.lat;
            const driver_long = driver_coords.long;

            const res = await fetch(
                MAPBOX_DIRECTION_API_ENDPOINT +
                currentLocation?.long +
                ',' +
                currentLocation?.lat +
                ';' +
                driver_long +
                ',' +
                driver_lat +
                '?overview=full&geometries=geojson' +
                '&access_token=' +
                MAPBOX_API_KEY,
                {
                headers: {
                    'Content-Type': 'application/json',
                },
                }
            );

            const result = await res.json();
            const distance_result = parseFloat(result.routes[0].distance * 0.000621371392);

            console.log('Mr ' + driver.first_name + ' ' + driver.last_name + ' is ', distance_result.toFixed(2) + ' km away from you');

            if (distance_result < old_distance || closest_driver === null) {
                shortest_distance = distance_result;
                closest_driver = driver;
            }

            old_distance = distance_result;
        };

        const fetchPromises = available_driver_list.map((driver) => fetch_distance(driver));


        await Promise.all(fetchPromises);

        console.log('');
        console.log(`The closest driver is (id ${closest_driver?.id}) ${closest_driver?.first_name} ${closest_driver?.last_name} because he is (${(shortest_distance).toFixed(2)} km) away.`);
        console.log('');

        // const closest_driver_coords = JSON.parse(closest_driver.current_location);
        // const closest_driver_lat = closest_driver_coords.lat;
        // const closest_driver_long = closest_driver_coords.long;

        //   setDriver(prevState => (
        //     {
        //       ...prevState,
        //       id: closest_driver?.id,
        //       name: closest_driver?.first_name,
        //       picture: closest_driver?.picture,
        //     }
        //   ));

        $('#closest_driver_id-input').val(closest_driver?.id);

    }

    mapboxgl.accessToken = MAPBOX_API_KEY;
    mapboxgl.logoEnabled = false;
    mapboxgl.scaleBarEnabled = false;

    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/satellite-streets-v12', // Mapbox style URL
        center: [35.0156,-15.8096], // Starting position (longitude, latitude)
        zoom: 14, // Starting zoom level
        logoPosition: 'top-right',
        logoEnabled: false
    });

    function flyTo(coordinates) {
        map.flyTo({
            center: [coordinates.long, coordinates.lat],
            essential: true // this animation is considered essential with respect to prefers-reduced-motion
        })
    }

    function addMarker(name, coordinates, color) {
        name = new mapboxgl.Marker({
            color: color
        }).setLngLat([coordinates.long,coordinates.lat])
        .addTo(map);
    }
});
