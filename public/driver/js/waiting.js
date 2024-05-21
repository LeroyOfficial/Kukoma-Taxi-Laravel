const API_ENDPOINT = 'http://localhost/kukoma-taxi/public/driver/'

const MAPBOX_API_KEY = 'pk.eyJ1IjoibGVyb3ktb2ZmaWNpYWwiLCJhIjoiY2xuOHVrczY3MDFtZzJrcWdyZ2E2ejdnaiJ9.ye5kWKO5CVGRoBLVGVtBKg'

const MAPBOX_DIRECTION_API_ENDPOINT ="https://api.mapbox.com/directions/v5/mapbox/driving/"

let currentLocation = {
    long: null,
    lat: null,
};

let fromCoordinates = {
    lat: null,
    long: null,
};

let toCoordinates = {
    lat: null,
    long: null,
};

let driverCoords = {
    lat: null,
    long: null,
}

let driver_status = null;

let trip = null

let pickupDirectionData = null;
let destinationDirectionData = null;

let distance = 0;

let duration = 0;

let seconds = 3000;

let max_count = 10;

$(document).ready(function() {
    function checkDriverStatus() {
        $.ajax({
            url: API_ENDPOINT+'check_online_status',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    if(response.driver_status) {
                        driver_status = response.driver_status;

                        if(response.driver_status == 'booked')
                            {
                                driver_status = 'online';
                            }

                        console.log ('driver status is '+ driver_status);

                        if(driver_status == 'online')
                            {
                                check_for_trips_count = 0;
                                checkForTrips();
                                getLocationLoop();
                                $('#online-div').removeClass('d-none');
                                $('#driver-history-div').addClass('d-none');
                            }

                        if(driver_status == 'offline')
                            {
                                $('#offline-div').removeClass('d-none');
                                $('#driver-history-div').removeClass('d-none');
                            }
                    }
                } else {
                    console.error('Error checking driver status :', response.message);
                    alert("Error checking 'Driver Status' request.. check console for more info");
                }
            },
            error: function(error) {
                console.error('Error:', error, error.message);
                alert("Error checking 'Driver Status' request.. check console for more info");
            }
        });
    }

    checkDriverStatus();

    function goOnline() {
        console.log('going online...');
        $.ajax({
            url: API_ENDPOINT+'go_online',
            type: 'GET',
            beforeSend: function() {
                $('#online-btn').attr('disabled',true);
                $('#online-btn').text('Loading...');
                $("#search-result").html("Searching..."); // Display a loading message
            },
            success: function(response) {
                $('#online-btn').attr('disabled',false);
                $('#online-btn').text('Go Online');

                if (response.success) {
                    checkDriverStatus();
                    console.log('respone message : '+ response.message);
                    alert(response.message);
                    $('#offline-div').addClass('d-none');
                    $('#online-div').removeClass('d-none');
                    $('#driver-history-div').addClass('d-none');
                } else {
                    console.error("Error sending 'Go Online' request : ", response.message);
                    alert("Error sending 'Go Online' request.. check console for more info");
                }
            },
            error: function(error) {
                console.error('Error:', error, error.message);
                alert("Error sending 'Go Online' request.. check console for more info");
            }
        });
    }

    $('#online-btn').click(function() {
        goOnline();
    })

    function goOffline() {
        console.log('going offline...');
        $.ajax({
            url: API_ENDPOINT+'go_offline',
            type: 'GET',
            beforeSend: function() {
                $('#offline-btn').attr('disabled',true);
                $('#offline-btn').text('Loading...');
                $("#search-result").html("Searching..."); // Display a loading message
            },
            success: function(response) {
                $('#offline-btn').attr('disabled',false);
                $('#offline-btn').text('Go Offline');
                if (response.success) {
                    checkDriverStatus();
                    console.log('respone message : '+ response.message);
                    alert(response.message);
                    $('#offline-div').removeClass('d-none');
                    $('#online-div').addClass('d-none');
                    $('#driver-history-div').removeClass('d-none');
                } else {
                    console.error("Error sending 'Go Offline' request : ", response.message);
                    alert("Error sending 'Go Offline' request.. check console for more info");
                }
            },
            error: function(error) {
                $('#offline-btn').attr('disabled',false);
                $('#offline-btn').text('Go Offline');
                console.error('Error:', error, error.message);
                alert("Error sending 'Go Offline' request.. check console for more info");
            }
        });
    }

    $('#offline-btn').click(function() {
        goOffline();
    })

    $('start-btn').click(function() {
        acceptTrip();
    })

    function getDriverLocation() {
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

            const coordinates = {
                lat: latitude,
                long: longitude,
            }

            updateDriverLocationInDB();
            addDriverLocationMarker(currentLocation);
            flyTo(currentLocation);

            $('#from-span').text('Current Location');

            // getTripDirections();

            if(trip)
                {
                    if(!pickupDirectionData)
                        {
                            getPickupRoute();
                        }

                    if(!destinationDirectionData)
                        {
                            getDestinationRoute();
                        }
                }


            },
            (error) => {
            handleError(error);
            }
        );
        } else {
        console.error("Geolocation is not supported by this browser.");
        }
    }

    function updateDriverLocationInDB() {

        $.ajax({
            url: API_ENDPOINT+'update_location',
            type: 'GET',
            data: {
                lat: currentLocation.lat ,
                long: currentLocation.long,
            },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    console.log('driver location updated successfully');
                } else {
                    $('#start-btn').attr('disabled',false);
                    $('#start-btn').text('Start');
                    console.error('Error sending update Driver Location request : ', response.message);
                    alert("Error sending 'update Driver Location' request.. check console for more info");
                }
            },
            error: function(error) {
                console.error('Error:', error, error.message);
                alert("Error sending 'update Driver Location' request.. check console for more info");

            }
        });
    }

    function getLocationLoop() {
        if(driver_status == 'online')
            {
                getDriverLocation();
                setTimeout(getLocationLoop, 5000);
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

    getDriverLocation();

    function getPickupRoute() {
        const url = MAPBOX_DIRECTION_API_ENDPOINT+
            currentLocation?.long+","+currentLocation?.lat+";"+
            trip.pickup.long+","+trip.pickup.lat+
            "?overview=full&geometries=geojson"+
            "&access_token="+MAPBOX_API_KEY;

            // if(!currentLocation.lat || !currentLocation.long)
            //     {
            //         getDriverLocation();
            //         return;
            //     }

        $.ajax({
            url: url,
            dataType: 'json',
            success: function(data) {
                if (data.code === 'Ok') {
                    const route = data.routes[0];
                    console.log('route data = ' + route);

                    if(route.geometry.coordinates)
                        {
                            pickupDirectionData = route.geometry.coordinates;
                            console.log('pickup route directions are', pickupDirectionData)
                            addRouteToMap('pickup-route','yellow',pickupDirectionData);
                        }

                    const distance_result = route.distance*0.000621371392;

                    console.log('distance is ',distance_result);

                    distance = distance_result.toFixed(1);

                    const duration_result = route.duration/60;
                    // console.log('duration is ', duration_result);

                    // duration = duration_result.toFixed(0);
                } else {
                    console.error('Error retrieving directions:', data.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error during AJAX request:', textStatus, errorThrown);
            }
        });
    }

    function getDestinationRoute() {
        const url = MAPBOX_DIRECTION_API_ENDPOINT+
            trip?.pickup.long+","+trip?.pickup.lat+";"+
            trip?.destination.long+","+trip?.destination.lat+
            "?overview=full&geometries=geojson"+
            "&access_token="+MAPBOX_API_KEY;

        if(!currentLocation.lat || !currentLocation.long)
            {
                getDriverLocation();
                return;
            }

        $.ajax({
            url: url,
            dataType: 'json',
            success: function(data) {
                if (data.code === 'Ok') {
                    const route = data.routes[0];
                    console.log('route data = ' + route);

                    if(route.geometry.coordinates)
                        {
                            destinationDirectionData = route.geometry.coordinates;
                            console.log('destination route directions are', destinationDirectionData)
                            addRouteToMap('destination-route','blue',destinationDirectionData);
                        }

                    const distance_result = route.distance*0.000621371392;

                    console.log('distance is ',distance_result);

                    distance = distance_result.toFixed(1);
                    $('.distance').text(distance);


                    const duration_result = route.duration/60;
                    console.log('duration is ', duration_result);

                    duration = duration_result.toFixed(0);
                    $('.duration').text(duration);
                } else {
                    console.error('Error retrieving directions:', data.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error during AJAX request:', textStatus, errorThrown);
            }
        });
    }

    function addRouteToMap(name, color,route) {
        map.addSource(name, {
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
            'id': name,
            'type': 'line',
            'source': name,
            'layout': {
                'line-join': 'round',
                'line-cap': 'round'
            },
            'paint': {
                'line-color': color,
                'line-width': 4
            }
        });

    }

    let check_for_trips_count = 0;

    function checkForTrips() {
        if(!trip && driver_status == 'online')
            {
                console.log("checking for trips " + check_for_trips_count);

                check_for_trips_count = check_for_trips_count + 1;

                if(check_for_trips_count > (100 || max_count))
                    {
                        alert('max fetch count reached.. refresh page to try again')
                        return;
                    }

                $.ajax({
                    url: API_ENDPOINT+'check_for_trips',
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            if(response.trip) {
                                trip = response.trip;
                                console.log('trip available '+ trip);
                                alert('New Trip Found click ok for more info');

                                $('#booked').removeClass('d-none');
                                $('#waiting').addClass('d-none');
                                $('.pickup-name').text(trip.pickup.name);
                                $('.destination-name').text(trip.destination.name);

                                $('#pickup-distance').text(trip.remaining.driver.distance);
                                $('#pickup-duration').text(trip.remaining.driver.duration);

                                $('#destination-distance').text(trip.distance);
                                $('#destination-duration').text(trip.duration);

                                const pickup_coords = {
                                    lat: trip.pickup.lat,
                                    long: trip.pickup.long,
                                }

                                const destination_coords = {
                                    lat: trip.destination.lat,
                                    long: trip.destination.long,
                                }

                                const getPL = setTimeout(() => {
                                    addCustomerLocationMarker(pickup_coords);
                                    getPickupRoute();
                                  }, 1000);

                                return () => clearTimeout(getPL);


                                const getDL = setTimeout(() => {
                                    addMarker(destination_coords, 'green');
                                    getDestinationRoute();
                                }, 1000);

                                return () => clearTimeout(getDL);

                            }
                        } else {
                            console.error('Error checking trip status:', response.message);
                        }
                    },
                    error: function(error) {
                        console.error('Error:', error, error.message);
                    }
                });

                setTimeout(checkForTrips, seconds);
            }
    }

    function acceptTrip() {
        window.location.href ='trip/'+trip.id+'/accept';

        // $.ajax({
        //     url: API_ENDPOINT+'trip/'+trip.id+'/accept',
        //     type: 'GET',
        //     data: { remaining: trip.remaining },
        //     dataType: "json",
        //     beforeSend: function() {
        //         $('#start-btn').attr('disabled',true);
        //         $('#start-btn').text('Loading...');
        //         $("#search-result").html("Searching..."); // Display a loading message
        //     },
        //     success: function(response) {
        //         $('#start-btn').attr('disabled',false);
        //         $('#start-btn').text('Start');

        //         if (response.success) {
        //             console.log('respone message : '+ response.message);
        //             $('#booked').addClass('d-none');
        //         } else {
        //             $('#start-btn').attr('disabled',false);
        //             $('#start-btn').text('Start');
        //             console.error('Error sending accept trip request : ', response.message);
        //             alert("Error sending 'Accept Trip' request.. check console for more info");
        //         }
        //     },
        //     error: function(error) {
        //         $('#start-btn').attr('disabled',false);
        //         $('#start-btn').text('Start');
        //         console.error('Error:', error, error.message);
        //         alert("Error sending 'Accept trip' request.. check console for more info");

        //     }
        // });
    }

    $('#start-btn').click(function() {
        acceptTrip();
     });

    mapboxgl.accessToken = MAPBOX_API_KEY;
    mapboxgl.logoEnabled = false;
    mapboxgl.scaleBarEnabled = false;

    const map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/satellite-streets-v12',
        center: [35.0156,-15.8096],
        zoom: 14,
    });

    function flyTo(coordinates) {
        map.flyTo({
            center: [coordinates.long, coordinates.lat],
            essential: true // this animation is considered essential with respect to prefers-reduced-motion
        })
    }

    function addMarker(coordinates, color) {
        const marker = new mapboxgl.Marker({
            color: color
        }).setLngLat([coordinates.long,coordinates.lat])
        .addTo(map);

        // .addClassName('some-class')
    }

    function addDriverLocationMarker(coordinates) {

        const driverMarker = new mapboxgl.Marker({
            element: document.getElementById('driverMarker'),
        });

        driverMarker.remove();

        driverMarker.setLngLat([coordinates.long,coordinates.lat])
        .addTo(map);
    }

    function addCustomerLocationMarker(coordinates) {

        const customerMarker = new mapboxgl.Marker({
            element: document.getElementById('customerMarker'),
        });

        customerMarker.remove();

        customerMarker.setLngLat([coordinates.long,coordinates.lat])
        .addTo(map);
    }

    function addDestinationLocationMarker(coordinates) {

        const marker = new mapboxgl.Marker({
            element: document.getElementById('destinationMarker'),
        });

        marker.remove();

        marker.setLngLat([coordinates.long,coordinates.lat])
        .addTo(map);
    }
});
