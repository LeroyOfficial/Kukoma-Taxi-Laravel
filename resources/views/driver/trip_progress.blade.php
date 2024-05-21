<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    @php
        $page_title = 'Trip in Progress';
    @endphp
    @include('components.new.driver.css')
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>
</head>

<body>
    <span class="trip_id d-none">{{$trip->id}}</span>

    <span class="trip_distance d-none">{{$trip->distance}}</span>
    <span class="trip_status d-none">{{$trip->status}}</span>


    @include('components.new.driver.nav')

    <div style="height: 10vh;">
    </div>
    <div class="section">
        <div class="row">
            <div class="col-md-6">

                <div id="going-to-pickup" class="d-none">
                    <div class="text-center py-2">
                        <i class="fas fa-location-arrow text-white bg-main fs-1 p-3 rounded-circle"></i>
                    </div>
                    <div class="text-center py-2">
                        <h1 id="pickup-name" class="mb-4"></h1>
                        <h1>
                            <span class="pickup-distance distance me-2"></span>
                            <span class="">KM</span>
                        </h1>
                        <h1>
                            <span class="pickup-duration duration me-2"></span>
                            <span class="">minutes</span>
                        </h1>
                    </div>
                </div>

                <div id="arrived-at-pickup-point" class="d-none h-100 mb-5 text-center d-flex align-items-center justify-content-center">
                    <div class="">
                        <h5 class="text-capitalize mb-4">You have arrived at pickup point</h5>

                        <div id="start-going-btn-div" class="text-center">
                            <button class="btn btn-main start-going-btn rounded-pill fs-5 py-2 px-5" type="button">start going to destination</button>
                        </div>
                    </div>

                </div>

                <div id="going-to-destination" class="d-none">
                    <div class="text-center py-2">
                        <i class="fas fa-location-arrow text-white bg-main fs-1 p-3 rounded-circle"></i>
                    </div>
                    <div class="text-center py-2">
                        <h1 class="mb-4 destination-name"></h1>
                        <h1>
                            <span class="destination-distance me-2"></span>
                            <span class="">KM</span>
                        </h1>
                        <h1>
                            <span class="destination-duration me-2"></span>
                            <span class="">minutes</span>
                        </h1>
                    </div>
                </div>

                <div id="arrived-at-destination" class="py-4 d-none">
                    <div id="send-review-div" class="text-center py-4">
                        <i class="fas fa-glass-cheers fs-1 color-main mb-4"></i>
                        <h1>You have arrived to your Destination</h1>
                        <div>
                            <button class="btn btn-main complete-btn rounded-pill fs-5 py-2 px-4" type="button">go back to dashboard</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div id='map' style='width: 100%; height: 70vh;'></div>
                <div class="d-none">
                    <div id="driverMarker" class="text-center">
                        <img src="{{asset('Driver_Pics/'.$driver->picture_url)}}" alt="Driver Marker Image" style="height: 50px; width: 30px" class="rounded-circle mb-1">
                        <br/>
                        <span class="fw-bold bg-main p-1 rounded-pill">{{$driver->first_name}} {{$driver->last_name}}</span>
                    </div>
                    <div id="customerMarker" class="text-center">
                        <img src="{{asset('admin/assets/images/faces/5.jpg')}}" alt="Customer Marker Image" style="height: 50px; width: 30px" class="rounded-circle mb-1">
                        <br/>
                        <span class="fw-bold bg-main p-1 rounded-pill">Customer</span>
                    </div>
                    <div id="destinationMarker" class="text-center">
                        <img src="{{asset('admin/assets/images/faces/5.jpg')}}" alt="Destination Marker Image" style="height: 50px; width: 30px" class="rounded-circle mb-1">
                        <br/>
                        <span class="fw-bold bg-main p-1 rounded-pill destination-">Destination</span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('components.new.driver.footer')
    <script src={{asset("driver/js/trip_progress.js")}}>
    </script>
</body>

</html>
