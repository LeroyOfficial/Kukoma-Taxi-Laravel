<!DOCTYPE html>
<html lang="en">

<?php
    // $active_page = 'Driver - '.$driver->first_name . ' ' . $driver->last_name;
    $active_page = 'Driver';
?>

<head>
    @include('components.new.admin.css')
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>
</head>

<body>
    <div id="app">
        @include('components.new.admin.sidebar')

        <div id="main">
            @include('components.new.admin.header')

            <div class="page-heading">
                <h3>Driver Details</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>{{$driver->first_name}} {{$driver->last_name}} Details</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-5">
                                            <div class="col-12 col-lg-3">
                                                <img src="{{asset('Driver_Pics/'.$driver->picture_url)}}" alt="">
                                            </div>
                                            <div class="col-12 col-lg-7">
                                                <div class="row row-cols-md-2">
                                                    <div class="mb-3">
                                                        <p class="fw-bold m-0">Name</p>
                                                        <p class="m-0">{{$driver->first_name}} {{$driver->last_name}}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <p class="fw-bold m-0">Status</p>
                                                        <p class="m-0">{{$driver->status}}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <p class="fw-bold m-0">Phone Number</p>
                                                        <p class="m-0">{{$driver->phone_number}}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <p class="fw-bold m-0">Email</p>
                                                        <p class="m-0">{{$driver->email}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($trip_count > 0)
                                            <div class="row mb-5">
                                                <div class="col-12 col-lg-9">
                                                    <div class="d-flex justify-content-between">
                                                        <h6>{{$driver->first_name}} {{$driver->last_name}} Trips</h6>
                                                        <a href="{{url('admin/driver/'.$driver->id.'/generate_report')}}" role="button" class="btn btn-main">Generate Report</a>
                                                    </div>
                                                    <div class="table-responsive">
                                                        <table class="table" id="table1">
                                                            <thead>
                                                                <tr>
                                                                    <th>Customer Name</th>
                                                                    <th>Pickup</th>
                                                                    <th>Destination</th>
                                                                    {{-- <th>Distance</th> --}}
                                                                    <th>Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($trips as $trip)
                                                                    <tr>
                                                                        <td class="col-3">
                                                                            <div class="d-flex align-items-center">
                                                                                <div class="avatar avatar-md">
                                                                                    <img src="{{asset('admin/assets/images/faces/5.jpg')}}">
                                                                                </div>
                                                                                <a href="{{url('admin/user/'.$user->where('id',$trip->user_id)->value('id'))}}" class="font-bold ms-3 mb-0">{{$user->where('id',$trip->user_id)->value('name')}}</a>
                                                                            </div>
                                                                        </td>
                                                                        <?php $pickupData = json_decode($trip->pickup, true); ?>
                                                                        <td class="col-auto">
                                                                            <p class=" mb-0">{{$pickupData['name']}}</p>
                                                                        </td>
                                                                        <td class="col-auto">
                                                                            <p class=" mb-0">{{$address->where('id',$trip->destination)->value('name')}}</p>
                                                                        </td>
                                                                        {{-- <td class="col-auto">
                                                                            <p class=" mb-0">{{$trip->distance}}km</p>
                                                                        </td> --}}
                                                                        <td class="col-auto">
                                                                            <td class="col-auto">
                                                                                <p class=" mb-0">{{$trip->created_at->format('d M Y')}}</p>
                                                                            </td>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-lg-3 pt-5">
                                                    <div class="row mb-4">
                                                        <div class="col-md-4">
                                                            <div class="stats-icon red">
                                                                <i class="iconly-boldBookmark"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <h6 class="text-muted font-semibold">Total Revenue</h6>
                                                            <h6 class="font-extrabold mb-0">K{{$total_revenue}}</h6>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-4">
                                                        <div class="col-md-4">
                                                            <div class="stats-icon red">
                                                                <i class="iconly-boldBookmark"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <h6 class="text-muted font-semibold">Total Trips</h6>
                                                            <h6 class="font-extrabold mb-0">{{$trip_count}}</h6>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-4">
                                                        <div class="col-md-4">
                                                            <div class="stats-icon red">
                                                                <i class="iconly-boldBookmark"></i>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <h6 class="text-muted font-semibold">Total Distance</h6>
                                                            <h6 class="font-extrabold mb-0">{{$total_distance}} km</h6>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        @else
                                            <h3 class="text-center">
                                                It seems that {{$driver->first_name}} {{$driver->last_name}} hasn't taken any trips yet
                                            </h3>
                                        @endif

                                        
                                        <?php $driverLocation = json_decode($driver->current_location, true); ?>
                                        <div class="d-none">
                                            <span id="driver_id">{{$driver->id}}</span>
                                            <span id="driver_lat" class="driver_lat">{{$driverLocation['lat']}}</span>
                                            <span id="driver_long" class="driver_long">{{$driverLocation['long']}}</span>
                                        </div>

                                        <div id="map-div" class="mt-5 d-none">
                                            <h6>{{$driver->first_name}} {{$driver->last_name}} Location</h6>
                                            <div class="col w-100">
                                                <div id='map' style='width: 100%; height: 70vh;'></div>
                                                <div class="d-none">
                                                    <div id="driverMarker" class="text-center">
                                                        <img src="{{asset('Driver_Pics/'.$driver->picture_url)}}" alt={{$driver->first_name."'s Image"}} class="rounded-circle" style="height: 50px; width: 30px">
                                                        <br/>
                                                        <span class="fw-bold color-main">{{$driver->first_name}} {{$driver->last_name}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            @include('components.new.admin.footer')

            <script>
                const MAPBOX_API_KEY = 'pk.eyJ1IjoibGVyb3ktb2ZmaWNpYWwiLCJhIjoiY2xuOHVrczY3MDFtZzJrcWdyZ2E2ejdnaiJ9.ye5kWKO5CVGRoBLVGVtBKg'

                const MAPBOX_DIRECTION_API_ENDPOINT ="https://api.mapbox.com/directions/v5/mapbox/driving/"

                mapboxgl.accessToken = MAPBOX_API_KEY;
                mapboxgl.logoEnabled = false;
                mapboxgl.scaleBarEnabled = false;

                const map = new mapboxgl.Map({
                    container: 'map',
                    style: 'mapbox://styles/mapbox/satellite-streets-v12',
                    center: [35.0156,-15.8096],
                    zoom: 14,
                });

                function addDriverMarker(driver_location) {
                    const marker = new mapboxgl.Marker({
                        element: document.getElementById('driverMarker'),
                    });

                    marker.remove();
        
                    marker.setLngLat([driver_location.long,driver_location.lat])
                    .addTo(map);
                }

                function flyTo(coordinates) {
                    map.flyTo({
                        center: [coordinates.long, coordinates.lat],
                        essential: true // this animation is considered essential with respect to prefers-reduced-motion
                    })
                }
                $(document).ready(function() {
                    const driver_location = {
                        lat: null,
                        long: null,
                    }

                    var lat = $('#driver_lat').text();
                    var long = $('#driver_long').text();

                    driver_location.lat = lat;

                    driver_location.long = Number(long);

                    console.log('driver location lat('+ driver_location.lat +') long('+ driver_location.long +')');

                    if(driver_location.lat)
                        {
                            addDriverMarker(driver_location);

                            flyTo(driver_location);

                            $('#map-div').removeClass('d-none');
                        }

                    // getDriverLocation();

                    function getDriverLocation() {
                        // var driver_id = $('#driver_id').text();

                        var driver_id = document.getElementById("driver_id");

                        $.ajax({
                            url: '/admin/driver/'+driver_id+'/get-driver-location',
                            type: 'GET',
                            success: function(data) {
                                driverCoords = JSON.parse(data.driver_location);

                                driver_location.lat = driverCoords.lat;
                                driver_location.long = driverCoords.long;

                                addDriverMarker(driver_location);

                                flyTo(driver_location);

                                $('#map-div').removeClass('d-none');
                            },
                            error: function(error) {
                                console.error('Error:', error, error.message);
                            }
                        });
                    }
                });
            </script>
</body>

</html>
