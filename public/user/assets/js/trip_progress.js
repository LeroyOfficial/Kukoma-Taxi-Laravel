const API_ENDPOINT = 'http://localhost/kukoma-taxi/public/user/'

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

let directionData = null;

let distance = 0;

let duration = 0;

let trip_id =null;

let trip_status = null;

let seconds = 3000;

let max_count = 10;



$(document).ready(function() {

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

    function addMarker(coordinates, color) {
        const marker = new mapboxgl.Marker({
            color: color
        }).setLngLat([coordinates.long,coordinates.lat])
        .addTo(map);

        // .addClassName('some-class')
    }

    function addDriverLocationMarker(coordinates) {

        const marker = new mapboxgl.Marker({
            element: document.getElementById('driverMarker'),
            rotation: -90,
        });

        marker.remove();

        marker.setLngLat([coordinates.long,coordinates.lat])
        .addTo(map);
    }

    function getDirections() {
        const url = MAPBOX_DIRECTION_API_ENDPOINT+
            currentLocation?.long+","+currentLocation?.lat+";"+
            toCoordinates?.long+","+toCoordinates?.lat+
            "?overview=full&geometries=geojson"+
            "&access_token="+MAPBOX_API_KEY;

        if(!currentLocation.lat || !currentLocation.long)
            {
                getUserLocation();
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
                            directionData = route.geometry.coordinates;
                            console.log('trip route directions are', directionData)
                            addRouteToMap('trip','blue',directionData);
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

            $('#pickup').val((`Current Location (Latitude: ${latitude}, Longitude: ${longitude})`));

                const coordinates = {
                    lat: latitude,
                    long: longitude,
                }

                addMarker(coordinates, 'red')
                // flyTo(coordinates);

                $('#from-span').text('Current Location');

                getDirections();

                if(trip_status == 'driver is coming')
                    {
                        getDriverRoute();
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

    getUserLocation();

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

    function setupTripOnMap(pickup_coords, destination_coords) {
        const pickup = {
            lat: pickup_coords.lat,
            long: pickup_coords.long,
        };

        addMarker(pickup, 'red');

        flyTo(pickup);

        const destination = {
            lat: destination_coords.lat,
            long: destination_coords.long
        };

        addMarker(destination, 'green');

        flyTo(destination);
    }

    console.log($('.trip_pickup').text());

    var trip_pickup = JSON.parse($('.trip_pickup').text());

    var trip_destination1 = $('.trip_destination').text();

    console.log('trip destination1 is ' + trip_destination1);

    var trip_destination = JSON.parse(trip_destination1);

    console.log('trip destination is ' + trip_destination + ' lat ('+ trip_destination.lat+') long('+trip_destination.long+')');

    toCoordinates.lat = trip_destination.lat;
    toCoordinates.long = trip_destination.long;

    const pickup_coords = {
        lat: trip_pickup.lat,
        long: trip_pickup.long
    }

    const destination_coords = {
        lat: trip_destination.lat,
        long: trip_destination.long
    }

    $('.pickup-span').text(trip_pickup.name);

    setupTripOnMap(pickup_coords, destination_coords);

    trip_id = $(document).find('.trip_id').text();
    console.log('trip ID is ' + trip_id)

    trip_status = $(document).find('.trip_status').text();
    console.log('trip Status is ' + trip_status)


    let check_driver_has_accepted_count = 0;

    function checkingIfDriverHasAccepted() {
        if (trip_status == "waiting for driver") {

            console.log("waiting for driver " + check_driver_has_accepted_count);

            check_driver_has_accepted_count = check_driver_has_accepted_count + 1;

            if(check_driver_has_accepted_count > (100 || max_count))
                {
                    alert('max fetch count reached.. refresh page to try again')
                    return;
                }

            $.ajax({
                url: API_ENDPOINT+'trip/'+trip_id+'/check-trip-status',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        if(response.message == 'driver is coming')
                            {
                                alert('Driver has accepted your trip');

                                $('#waiting-for-driver').addClass('d-none');
                                $('#driver-is-coming').removeClass('d-none');

                                console.log('response = ' + response.message)
                                trip_status = 'driver is coming'

                                checkingDriverDistance();
                            }
                    } else {
                        console.error('Error checking trip status:', response.message);
                    }
                },
                error: function(error) {
                    console.error('Error:', error, error.message);
                }
            });

            setTimeout(checkingIfDriverHasAccepted, seconds);
        }
    }

    let check_driver_distance_count = 0;

    function checkingDriverDistance() {
        if (trip_status == "driver is coming") {
            console.log("driver is coming " + check_driver_distance_count);

            check_driver_distance_count = check_driver_distance_count + 1;

            if(check_driver_distance_count > (100||max_count))
            {
                alert('max fetch count reached.. refresh page to try again')
                return;
            }

            if(trip_remaining.driver.distance < 1)
            {
                alert('Driver has arrived to pickup point')
                return;
            }

            getDriverLocation();

            setTimeout(checkingDriverDistance, seconds);
        }
    }

    const trip_remaining = {
            driver:
                {
                    distance:2,
                    time:5
                },
            destination:
                {
                    distance:2,
                    time:5
                }
    }

    function getDriverLocation() {
        $.ajax({
            url: API_ENDPOINT+'trip/'+trip_id+'/check-driver-location',
            type: 'GET',
            success: function(data) {
                driverCoords = JSON.parse(data.driver_location);

                console.log('driver location is lat(' + driverCoords.lat +') long('+ driverCoords.long +')');

                addDriverLocationMarker(driverCoords);

                flyTo(driverCoords);

                var remaining = JSON.parse(data.remaining);

                trip_remaining.driver.distance = remaining.driver.distance;
                    $('.driver-distance').text(trip_remaining.driver.distance);
                    if(trip_remaining.driver.distance <= 1 )
                        {
                            alert('Driver has arrived at pickup point');
                            $('#driver-is-coming').addClass('d-none');
                            $('#arrived-at-pickup-point').removeClass('d-none');

                            trip_status = "arrived at pickup point";
                            map.removeSource('route-from-driver');
                            return;
                        }

                trip_remaining.driver.time = remaining.driver.time;
                $('.driver-duration').text(trip_remaining.driver.time);

                console.log("driver remaining distance ("+trip_remaining.driver.distance+") time ("+trip_remaining.driver.time+")")

            },
            error: function(error) {
                console.error('Error:', error, error.message);
            }
        });
    }

    function getDriverRoute() {
        const url = MAPBOX_DIRECTION_API_ENDPOINT+
            currentLocation?.long+","+currentLocation?.lat+";"+
            driverCoords?.long+","+driverCoords?.lat+
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
                            addRouteToMap('route-from-driver','yellow',directionData);
                        }

                    const distance_result = route.distance*0.000621371392;

                    console.log('distance is ',distance_result);

                    distance = distance_result.toFixed(1);
                    if(distance <= 1 )
                        {
                            alert('Driver has arrived at pickup point');
                            $('#driver-is-coming').addClass('d-none');
                            $('#arrived-at-pickup-point').removeClass('d-none');

                            trip_status = "arrived at pickup point";
                            map.removeSource('route-from-driver');
                        }


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

    let check_distance_to_destination_count = 0;

    function checkingDistanceToDestination() {
        if (trip_status == "going to destination") {
            console.log("going to destination " + check_distance_to_destination_count);

            check_distance_to_destination_count = check_distance_to_destination_count + 1;

            if(check_distance_to_destination_count > (100 || max_count))
                {
                    alert('max fetch count reached.. refresh page to try again')
                    return;
                }

                $.ajax({
                    url: API_ENDPOINT+'trip/'+trip_id+'/going-to-destination',
                    type: 'GET',
                    success: function(data) {
                        driverCoords = JSON.parse(data.driver_location);

                        console.log('driver location is lat(' + driverCoords.lat +') long('+ driverCoords.long +')');

                        addDriverLocationMarker(driverCoords);

                        flyTo(driverCoords);

                        var remaining = JSON.parse(data.remaining);

                        trip_remaining.destination.distance = remaining.destination.distance;
                            $('.destination-distance').text(trip_remaining.destination.distance);
                            if(trip_remaining.destination.distance < 1 )
                                {
                                    arrivedAtDestination();
                                    return;
                                }

                        trip_remaining.destination.time = remaining.destination.time;
                        $('.destination-duration').text(trip_remaining.destination.time);

                        console.log("Destination remaining distance ("+trip_remaining.destination.distance+") time ("+trip_remaining.driver.time+")")

                    },
                    error: function(error) {
                        console.error('Error:', error, error.message);
                    }
                });

            setTimeout(checkingDistanceToDestination, seconds);
        }
    }

    function acceptTrip() {
        $.ajax({
            url: API_ENDPOINT+'trip/'+trip_id+'/accept',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    console.log('respone message'+ response.message);

                    $('#ok-div').addClass('d-none');
                    $('trip-price-div').addClass('d-none');

                    $('#waiting-for-driver').removeClass('d-none');

                    trip_status = 'waiting for driver';

                    checkingIfDriverHasAccepted();
                } else {
                    console.error('Error sending accept trip request:', response.message);
                }
            },
            error: function(error) {
                console.error('Error:', error, error.message);
            }
        });
    }

    function cancelTrip() {
        $.ajax({
            url: API_ENDPOINT+'trip/'+trip_id+'/cancel',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    console.log('respone message'+ response.message);
                    window.location.href =API_ENDPOINT+'booking';
                } else {
                    console.error('Error sending cancel trip request:', response.message);
                }
            },
            error: function(error) {
                console.error('Error:', error, error.message);
            }
        });
    }

    function driverIsComing() {
        $('#pending').removeClass('d-none');
        $('#ok-div').addClass('d-none');
        $('#driver-is-coming').removeClass('d-none');

        if(!directionData)
            {
                getDirections();
            }

        getDriverRoute();

        checkingDriverDistance();
    }

    function arrivedAtPickupPoint() {
        $('#pending').removeClass('d-none');
            $('#ok-div').addClass('d-none');
            $('#arrived-at-pickup-point').removeClass('d-none');
            map.removeSource('route-from-driver');

            const coordinates = {
                lat: currentLocation.lat,
                long: currentLocation.long
            }

            flyTo(coordinates);

            addDriverLocationMarker(coordinates);
    }

    function startGoingToDestination() {
        $.ajax({
            url: API_ENDPOINT+'trip/'+trip_id+'/start_going',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    console.log('respone message'+ response.message);

                    $('#pending').addClass('d-none');
                    $('#going-to-destination').removeClass('d-none');
                    checkingDistanceToDestination();
                } else {
                    console.error('Error sending start going to destination request:', response.message);
                }
            },
            error: function(error) {
                console.error('Error:', error, error.message);
            }
        });
    }

    function arrivedAtDestination() {
        alert('You have arrived to your destination');
        $('#arrived-at-destination').removeClass('d-none');

        trip_status = "arrived at destination";

        $.ajax({
            url: API_ENDPOINT+'trip/'+trip_id+'/arrived',
            type: 'GET',
            success: function(response) {
                if (response.success) {

                } else {
                    console.error('Error sending Trip Arrived at destination request:', response.message);
                }
            },
            error: function(error) {
                console.error('Error:', error, error.message);
            }
        });
    }

    if(trip_status == 'pending')
        {
            $('#pending').removeClass('d-none');
            if(!directionData)
                {
                    getDirections();
                }
        }

    if(trip_status == 'waiting for driver')
        {
            $('#pending').removeClass('d-none');
            $('#ok-div').addClass('d-none');
            $('#waiting-for-driver').removeClass('d-none');

            if(!directionData)
                {
                    getDirections();
                }

            checkingIfDriverHasAccepted();
        }

    if(trip_status == 'driver is coming')
        {
            driverIsComing();
        }

    if(trip_status == 'arrived at pickup point')
        {
            arrivedAtPickupPoint();
        }

    if(trip_status == 'going to destination')
        {
            $('#going-to-destination').removeClass('d-none');

            checkingDistanceToDestination();

            const coordinates = {
                lat: currentLocation.lat,
                long: currentLocation.long
            }

            flyTo(coordinates);

            addDriverLocationMarker(coordinates);
        }

    if(trip_status == 'arrived at destination')
        {
            $('#arrived-at-destination').removeClass('d-none');

            const coordinates = {
                lat: currentLocation.lat,
                long: currentLocation.long
            }

            flyTo(coordinates);

            addDriverLocationMarker(coordinates);
        }

    $('.ok-btn').click(function() {
        acceptTrip();
     });

    $('.cancel-btn').click(function() {
        cancelTrip();
    });

    $('.start-going-btn').click(function() {
        startGoingToDestination();
    })

    $('#send-review-div .btn').click(function() {
        $('#send-review-div').addClass('d-none');
        $('#feedback-div').removeClass('d-none')
    })
});
