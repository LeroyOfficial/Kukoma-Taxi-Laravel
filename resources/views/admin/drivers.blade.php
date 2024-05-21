<!DOCTYPE html>
<html lang="en">

<?php $active_page = 'Drivers'; ?>

<head>
    @include('components.new.admin.css')
</head>

<body>
    <div id="app">
        @include('components.new.admin.sidebar')
        <div id="main">
            @include('components.new.admin.header')

            <div class="page-heading">
                <h3>Drivers</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between">
                                            <h4>List of Drivers</h4>

                                            <a href="{{url('admin/new_driver')}}" role="button" class="btn btn-main fw-bold">Add a new driver</a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                            @if ($driver_count > 0)
                                                @if (session()->has('message'))
                                                    @include('components.new.admin.alert')
                                                @endif
                                                <div class="table-responsive">
                                                    <table class="table" id="table1">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Phone Number</th>
                                                                <th>Email</th>
                                                                <th># of Trips</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($drivers as $driver)
                                                                <tr>
                                                                    <td class="col-3">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar avatar-md">
                                                                                <img src="{{asset('Driver_Pics/'.$driver->picture_url)}}">
                                                                            </div>
                                                                            <a href="{{url('admin/driver/'.$driver->id)}}" class="font-bold ms-3 mb-0">{{$driver->first_name}} {{$driver->last_name}}</a>
                                                                        </div>
                                                                    </td>
                                                                    <td class="col-auto">
                                                                        <p class=" mb-0">{{$driver->phone_number}}</p>
                                                                    </td>
                                                                    <td class="col-auto">
                                                                        <p class=" mb-0">{{$driver->email}}</p>
                                                                    </td>
                                                                    <td class="col-auto">
                                                                        <a href="{{url('admin/driver/'.$driver->id)}}">{{$trip_count->where('driver_id',$driver->id)->count()}}</a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <div id="driver-location-list" class="d-none">
                                                    @foreach ($drivers as $driver)
                                                        <div class="driver-location">
                                                            <span class="driver-id">{{$driver->id}}</span>
                                                            <span class="name">{{$driver->first_name}}</span>
                                                            <div class="Drivermarker">
                                                                <img src="{{asset('Driver_Pics/'.$driver->picture_url)}}" alt={{$driver->first_name."'s Image"}} class="rounded-circle" style="height: 50px; width: 30px">
                                                                <br/>
                                                                <span class="full_name">{{$driver->first_name}} {{$driver->last_name}}</span>
                                                            </div>
                                                        @if($driver->curremt_location)
                                                            <?php $driverLocation = json_decode($driver->current_location, true); ?>
                                                                <span class="lat">{{$driverLocation['lat']}}</span>
                                                                <span class="long">{{$driverLocation['long']}}</span>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>

                                                <div id="map-div" class="mt-5">
                                                    <h6>Drivers Location</h6>
                                                    <div class="col w-100">
                                                        <div id='map' style='width: 100%; height: 70vh;'></div>
                                                    </div>
                                                </div>
                                            @else
                                                <h3 class="text-center">
                                                    It seems that there are no drivers available
                                                <br>
                                                    <a href={{url('admin/new_driver')}} class="color-main">click here to add a driver</a>
                                                </h3>
                                            @endif
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

                function addDriverMarker(driver_location, name, marker) {
                    name = new mapboxgl.Marker({
                        element: marker,
                    });

                    name.remove();

                    name.setLngLat([driver_location.long,driver_location.lat])
                    .addTo(map);
                }

                function flyTo(coordinates) {
                    map.flyTo({
                        center: [coordinates.long, coordinates.lat],
                        essential: true // this animation is considered essential with respect to prefers-reduced-motion
                    })
                }

                $(document).ready(function() {

                    console.log("Processing div:");

                    $(".driver-location").each(function() {
                            console.log("Processing div:");
                            let driverId = 'driver-' + $(this).find('.driver-id').text();
                            let driverName = $(this).find('.name').text();
                            let lat = $(this).find('.lat').text();
                            let long = $(this).find('.long').text();
                            let marker = $(this).find('.driver-marker');

                            console.log('adding driver marker - ' + driverName);

                            let driverLocation = {
                                lat: lat,
                                long: long,
                            }
                            
                            // addDriverMarker(driverLocation, driverId, marker);

                            flyTo(driverLocation);
                        });

                        $('#map-div').removeClass('d-none');
                });

            </script>
</body>

</html>
