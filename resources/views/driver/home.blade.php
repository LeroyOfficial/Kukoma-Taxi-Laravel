<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    @php
        $page_title = 'Dashboard';
    @endphp
    @include('components.new.driver.css')
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>
</head>

<body>
    @include('components.new.driver.nav')

    <div id="page-hero" class="page-hero text-center text-white d-flex align-items-center justify-content-center">
        <div>
            <span class="text-capitalize">
                <a href={{url('user/dashboard')}}>Home</a>
                <span class="ms-1 color-main">// home</span>
            </span>
            <h1 class="text-uppercase fw-bold">home</h1>
        </div>
    </div>
    <div class="bg-main color-second px-4 py-4">
        <h1>Welcome {{Auth::user()->name}}</h1>
    </div>
    <div class="py-5 d-flex justify-content-center align-items-center" style="padding: 0px;">
        {{-- @if ($driver->status = 'offline') --}}
            <div id="offline-div" class="d-none">
                <div class="text-center py-2">
                    <i class="fas fa-location-arrow text-white bg-main fs-1 p-3 rounded-circle"></i>
                </div>
                <div class="text-center py-2">
                    <h1 class="mb-4">You are are currently offline</h1>

                    <button id="online-btn" class="btn btn-main btnn-lg rounded-pill">Go Online</button>
                </div>
            </div>
        {{-- @else --}}
            <div id="online-div" class="row d-none">
                <div class="col-md-6 mb-4">
                    <div id="waiting" class="">
                        <div class="text-center py-2">
                            <i class="fas fa-location-arrow text-white bg-main fs-1 p-3 rounded-circle"></i>
                        </div>
                        <div class="text-center py-2">
                            <h1 class="mb-4">You are are currently online</h1>

                            <h2 class="mb-4">Please be patient as you wait for near by customers to book you</h2>

                            <button id="offline-btn" class="btn btn-danger btn-lg rounded-pill">Go Offline</button>
                        </div>
                    </div>

                    <div id="booked" class="booked d-none">
                        <div class="text-center py-2">
                            <i class="fas fa-location-arrow text-white bg-main fs-1 p-3 rounded-circle"></i>
                        </div>
                        <div class="text-center py-2">
                            <h2 class="mb-4">You have been booked</h2>

                            <h5 class="mb-4">
                                <span>There is a customer waiting for you to pick them up at </span>
                                <span id="pickup-name" class="fw-bold pickup-name"></span>
                                <span> and drop them off at</span>
                                <span id="destination-name" class="fw-bold destination-name"></span>
                            </h5>
                            <h5 class="mb-4">
                                <span>Distance to pickup point is </span>
                                <span id="pickup-distance" class="pickup-distance">5</span><span>km</span>

                                <span>which will take </span><span class="pickup-duration">5</span><span> minutes</span>
                            </h5>
                            <h5 class="mb-4">
                                <span>Then Distance to destination is </span>
                                <span id="destination-distance" class="destination-distance">10</span><span>km</span>

                                <span>which will take </span><span class="destination-duration">10</span><span> minutes</span>
                            </h5>

                            <button id="start-btn" class="btn btn-main btn-lg rounded-pill">Start</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div id='map' style='width: 100%; height: 70vh;' class="w-100"></div>

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
        {{-- @endif --}}
    </div>

    <div id="driver-history-div" class="section d-none">
        <div class="px-4 py-4 bg-grey" style="min-height: 100vh;">
            <h3>Recent Trips</h3>
            <div id="recent-trip-list" class="py-4 pe-4 fw-bold fs-5" style="overflow-y: auto;max-height: 80vh;">

                @if ($trip_count > 0)
                    @foreach ($trips as $trip)
                        <div class="py-4 px-3 row rounded bg-white mb-4">
                            <div class="col-sm-6 col-md-6 col-lg-3">
                                <img
                                    class="rounded"
                                    src={{asset('Car_Pics/'.$car->where('id',$trip->car_id)->value('picture_url'))}}
                                    alt="car's picture"
                                />
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-3">
                                <p>
                                    {{$car->where('id',$trip->car_id)->value('brand')}}
                                    {{$car->where('id',$trip->car_id)->value('model')}}
                                    ({{$car->where('id',$trip->car_id)->value('number_plate')}})
                                    driven by me
                                </p>

                                <?php $pickupData = json_decode($trip->pickup, true); ?>
                                <p>From: {{$pickupData['name']}}</p>
                                <p>To: {{$address->where('id',$trip->destination)->value('name')}}</p>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-3">
                                <p>Distance: {{$trip->distance}} km</p>
                                <p>Duration: {{$trip->duration}} minutes</p>
                                <p>Price : K{{$trip->price}}</p>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-3">
                                <p>Date: {{$trip->created_at->format('d M Y')}}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <h3 class="text-center">
                        It seems that you havent taken any trips yet
                    </h3>
                @endif

            </div>
        </div>
    </div>

    @include('components.new.driver.footer')
    <script src={{asset("driver/js/waiting.js")}}></script>
</body>

</html>
