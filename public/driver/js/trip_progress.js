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

let trip = {
    id: null,
    user: {
        name: null,
    },
    driver_id: "15",
    pickup: {
       name: null,
        lat: null,
       long: null
    },
    destination: {
       name: null,
        lat: null,
       long: null
    },
    distance: null,
    duration: null,
    status: null,
    remaining: {
        driver: {
            distance: null,
           time: null
        },
        destination: {
            distance: null,
           time: null
        }
    }
}

let pickupDirectionData = null;
let destinationDirectionData = null;

let distance = 0;

let duration = 0;

let seconds = 3000;

let max_count = 30;



$(document).ready(function() {
    trip.id = $('.trip_id').text();

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

            if(trip.id)
                {
                    if(trip.status == 'driver is coming' && !pickupDirectionData)
                        {
                            getPickupRoute();
                        }

                    if(trip.status == ('arrived at pickup point' || 'going to destination') && !destinationDirectionData)
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

    let check_driver_distance_count = 0;
    let check_driver_distance_error = null;

    function checkDriverDistance() {
        if(!currentLocation.lat)
            {
                getDriverLocation();
            }
        if(currentLocation.lat && trip.status == 'driver is coming' && !check_driver_distance_error)
            {
                console.log('getting and posting driver distance and duration ' + check_driver_distance_count)
                check_driver_distance_count = check_driver_distance_count + 1;
            if(check_driver_distance_count > max_count)
                {
                    alert('max fetch count reached.. refresh page to try again');
                    return;
                }

                getDriverLocation();
                getPickupRoute();

                setTimeout(checkDriverDistance, seconds);
            }
    }

    function postDriverDistance() {
        $.ajax({
            url: API_ENDPOINT+'trip/'+trip.id+'/post-driver-distance',
            type: 'GET',
            data: {
                distance: trip.remaining.driver.distance ,
                time: trip.remaining.driver.time,
            },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    console.log('driver distance updated successfully');
                } else {
                    check_driver_distance_error = true;
                    console.error('Error sending update Driver distance request : ', response.message);
                    alert("Error sending 'update Driver distance' request.. check console for more info");
                }
            },
            error: function(error) {
                check_driver_distance_error = true;
                console.error('Error:', error, error.message);
                alert("Error sending 'update Driver Location' request.. check console for more info");

            }
        });
    }

    getDriverLocation();

    function getLocationLoop() {
        getDriverLocation();
        setTimeout(getLocationLoop, 5000);
    }

    function checkTripDetails() {
        $.ajax({
            url: API_ENDPOINT+'trip/'+trip?.id+'/get-trip-details',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    if(response.trip) {
                        if(response.trip)
                            {
                                trip = response.trip;

                                console.log('trip', trip)

                                console.log('trip status is ' + trip.status);

                                $('#pickup-name').text(trip.pickup.name);
                                $('.destination-name').text(trip.destination.name);

                                if(trip.status == 'driver is coming')
                                    {
                                        goingToPickupPoint();
                                    }

                                if(trip.status == 'arrived at pickup point')
                                    {
                                        arrivedAtPickupPoint();
                                    }

                                if(trip.status == 'going to destination')
                                    {
                                       startGoingToDestination();
                                    }

                                if(trip.status == 'arrived at destination')
                                    {
                                        arrivedAtDestination();
                                    }
                            }
                    }
                } else {
                    console.error('Error checking Trip Status :', response.message);
                    alert("Error checking 'Trip Status' request.. check console for more info");
                }
            },
            error: function(error) {
                console.error('Error:', error, error.message);
                alert("Error checking 'Trip Status' request.. check console for more info");
            }
        });
    }

    checkTripDetails();

    function goingToPickupPoint() {
        getLocationLoop();
        $('#going-to-pickup').removeClass('d-none');

        checkDriverDistance();
    }

    function getPickupRoute() {
        const url = MAPBOX_DIRECTION_API_ENDPOINT+
            currentLocation?.long+","+currentLocation?.lat+";"+
            trip.pickup.long+","+trip.pickup.lat+
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
                    console.log('pickup route data', route);

                    if(route.geometry.coordinates && !pickupDirectionData)
                        {
                            pickupDirectionData = route.geometry.coordinates;
                            console.log('pickup route directions are', pickupDirectionData)
                            addRouteToMap('pickup-route','yellow',pickupDirectionData);
                        }

                    const distance_result = route.distance*0.000621371392;

                    console.log('distance is ',distance_result);

                    trip.remaining.driver.distance = distance_result.toFixed(1);
                    $('.pickup-distance').text(trip.remaining.driver.distance);

                    if(trip.remaining.driver.distance <= 1)
                        {
                            arrivedAtPickupPoint();
                        }

                    const duration_result = route.duration/60;
                    console.log('duration is ', duration_result);

                    trip.remaining.driver.time = duration_result.toFixed(0);
                    $('.pickup-duration').text(trip.remaining.driver.time);

                    postDriverDistance();
                } else {
                    console.error('Error retrieving directions:', data.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error during AJAX request:', textStatus, errorThrown);
            }
        });
    }

    function arrivedAtPickupPoint() {
        if(trip.status != "arrived at pickup point")
            {
                postArrivedAtPickup();
            }

        $('#going-to-pickup').addClass('d-none');

        $('#arrived-at-pickup-point').removeClass('d-none');
    }

    function postArrivedAtPickup() {
        $.ajax({
            url: API_ENDPOINT+'trip/'+trip.id+'/arrived-pickup',
            type: 'GET',
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    if(response.message)
                        {
                            trip.status = "arrived at pickup point";
                            checkTripDetails();
                            console.log('respone message', response.message);
                            alert('You have arrived at pickup point');
                            $('going-to-pickup').addClass('d-none');
                            $('#arrived-at-pickup-point').removeClass('d-none');
                        }
                }
            },
            error: function(error) {
                console.error('Error:', error, error.message);
                alert("Error sending 'update arrived at pickup' request.. check console for more info");

            }
        });
    }

    function startGoingToDestination() {
        getLocationLoop();

        if(trip.status != "going to destination")
            {
                postGoingToDestination();
            }

        checkDestinationDistance();

        $('#arrived-at-pickup-point').addClass('d-none');
        $('#going-to-destination').removeClass('d-none');
    }

    function postGoingToDestination() {
        $.ajax({
            url: API_ENDPOINT+'trip/'+trip.id+'/going-to-destination',
            type: 'GET',
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    if(response.message)
                        {
                            trip.status = "going to destionation";
                            checkTripDetails();
                            checkDestinationDistance();
                            console.log('respone message', response.message);
                            // alert('You are going to destionation');
                            $('#arrived-at-pickup-point').addClass('d-none');
                            $('#going-to-destination').removeClass('d-none');
                        }
                }
            },
            error: function(error) {
                console.error('Error:', error, error.message);
                alert("Error sending 'update going to destination' request.. check console for more info");

            }
        });
    }
    let check_destination_distance_count = 0;
    let check_destination_distance_error = null;


    function checkDestinationDistance() {
        if(!currentLocation.lat)
            {
                getDriverLocation();
            }

        // if(currentLocation.lat && !check_destination_distance_error)
        //     {
                console.log('getting and posting driver distance and duration ' + check_destination_distance_count)
                check_destination_distance_count = check_destination_distance_count + 1;

                if(check_destination_distance_count > max_count)
                {
                    alert('max fetch count reached.. refresh page to try again');
                    return;
                }

                getDriverLocation();
                getDestinationRoute();

                setTimeout(checkDestinationDistance, seconds);
            // }
    }

    function getDestinationRoute() {
        const url = MAPBOX_DIRECTION_API_ENDPOINT+
            currentLocation.long+","+currentLocation.lat+";"+
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
                    console.log('destination route data = ' + route);

                    if(route.geometry.coordinates && !destinationDirectionData)
                        {
                            destinationDirectionData = route.geometry.coordinates;
                            console.log('destination route directions are', destinationDirectionData)
                            addRouteToMap('destination-route','blue',destinationDirectionData);
                        }

                    const distance_result = route.distance*0.000621371392;

                    console.log('distance is ',distance_result);

                    distance = distance_result.toFixed(1);
                    trip.remaining.destination.distance = distance;
                    $('.destination-distance').text(distance);

                    if(distance <= 1)
                        {
                            arrivedAtDestination();
                        }
                    const duration_result = route.duration/60;
                    console.log('duration is ', duration_result);

                    duration = duration_result.toFixed(0);
                    trip.remaining.destination.time = duration;
                    $('.destination-duration').text(duration);
                    postDestinationDistance();
                } else {
                    console.error('Error retrieving directions:', data.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error during AJAX request:', textStatus, errorThrown);
            }
        });
    }

    function postDestinationDistance() {
        $.ajax({
            url: API_ENDPOINT+'trip/'+trip.id+'/post-destination-distance',
            type: 'GET',
            data: {
                distance: trip.remaining.destination.distance ,
                time: trip.remaining.destination.time,
            },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    console.log('destination distance updated successfully');
                } else {
                    check_destination_distance_error = true;
                    console.error('Error sending update destination distance request : ', response.message);
                    alert("Error sending 'update destination distance' request.. check console for more info");
                }
            },
            error: function(error) {
                check_driver_distance_error = true;
                console.error('Error:', error, error.message);
                alert("Error sending 'update Driver Location' request.. check console for more info");

            }
        });
    }

    $('.start-going-btn').click(function() {
        startGoingToDestination();
    });

    function arrivedAtDestination() {
        if(trip.status != "arrived at destination")
            {
                postArrivedAtDestination();
            }
        $('#arrived-at-destination').removeClass('d-none');
    }

    function postArrivedAtDestination() {
        window.location.href = API_ENDPOINT+'trip/'+trip.id+'/arrived-destination';
    }

    $('.complete-btn').click(function() {
        finish();
    })
    function finish() {
        window.location.href =API_ENDPOINT+'history';
    }

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
            color: color            ``
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
});
