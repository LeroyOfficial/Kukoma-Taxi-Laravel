<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    @php
        $page_title = 'Trip in Progress';
    @endphp
    @include('components.new.user.css')
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>
</head>

<body>
    <span class="trip_id d-none">{{$trip->id}}</span>
    <span class="trip_pickup d-none">{{$trip->pickup}}</span>
    <span class="trip_destination d-none">{
        "name": "{{$address->name}}",
        "lat": {{$address->latitude}},
        "long": {{$address->longitude}}
    }</span>
    <span class="trip_distance d-none">{{$trip->distance}}</span>
    <span class="trip_status d-none">{{$trip->status}}</span>


    @include('components.new.user.nav')

    <div style="height: 10vh;">
    </div>
    <div class="section">
        <div class="row">
            <div class="col-md-6">

                <div id="pending" class="d-none">
                    <div id="driver-details" class="bg-main text-white d-flex row rounded-top">
                        <div class="col-9 py-2 d-flex flex-column justify-content-center">
                            <h2>{{$driver->first_name}} {{$driver->last_name}}</h2>
                            <div>
                                <i class="fas fa-star me-2"></i>
                                <i class="fas fa-star me-2"></i>
                                <i class="fas fa-star me-2"></i>
                                <i class="fas fa-star me-2"></i>
                                <i class="fas fa-star me-2"></i>
                            </div>
                        </div>
                        <div class="col-3 text-center py-2 d-flex align-items-center">
                            <img class="rounded-circle bg-white" src="{{asset('Driver_Pics/'.$driver->picture_url)}}" style="width: 100px;height: 100px;">
                        </div>
                    </div>
                    <div class="py-4 bg-white px-2">

                        <div id="waiting-for-driver" class="d-none">
                            <p>
                                Waiting for driver to accept the trip
                            </p>
                        </div>

                        <div id="driver-is-coming" class="d-none">
                            <p>
                                <span class="me-1">Your ride will arrive in</span>
                                <span class="me-1 driver-duration"></span>
                                <span>minutes</span>
                            </p>
                        </div>

                        <div id="car-details" class="row">
                            <div class="col-9">
                                <h5 class="text-capitalize">car type</h5>
                                <p class="fw-bold">{{$car->brand}} {{$car->model}}</p>
                                <p class="fw-bold">{{$car->number_plate}}</p>
                            </div>
                            <div class="col-3 d-flex align-items-center">
                                <img src={{asset('Car_Pics/'.$car->picture_url)}}>
                            </div>
                        </div>

                        <div id="trip-details" class="px-2">
                            <h5 class="text-capitalize">Trip Route</h5>
                            <p>
                                <span class="me-1 text-capitalize">From:</span>
                                <span class="pickup-span fw-bold"></span>
                            </p>
                            <p>
                                <span class="me-1 text-capitalize">To:</span>
                                <span class="destination-span fw-bold">{{$address->name}}</span>
                            </p>
                        </div>

                        <div id="trip-price-div" class="row d-none">
                            <div class="col-9">
                                <p class="text-capitalize">Total amount</p>
                            </div>
                            <div class="col-3">
                                <p class="fw-bold">
                                    <span>K</span>
                                    <span class="price-span-value">{{$trip->price}}</span>
                                </p>
                            </div>
                        </div>

                        <div id="ok-div" class="text-center">
                            <div class="py-2">
                                <button class="btn btn-main ok-btn rounded-pill fs-5 py-2 px-5" type="button">Ok</button>
                            </div>
                            <div class="py-2">
                                <a role="button" href="{{url('user/trip/'.$trip->id.'/cancel')}}" class="btn btn-danger cancel-btn btn-hover-second rounded-pill fs-5 py-2 px-5">cancel</a>
                            </div>
                        </div>

                        <div id="arrived-at-pickup-point" class="d-none">
                            <h5 class="text-capitalize mb-4">Driver has arrived at your Location</h5>

                            <div id="start-going-btn-div" class="text-center">
                                <button class="btn btn-main start-going-btn rounded-pill fs-5 py-2 px-5" type="button">start going to destination</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="going-to-destination" class="d-none">
                    <div class="text-center py-2">
                        <i class="fas fa-location-arrow text-white bg-main fs-1 p-3 rounded-circle"></i>
                    </div>
                    <div class="text-center py-2">
                        <h1 class="mb-4">Nacit, Blantyre</h1>
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
                            <button class="btn btn-main write-a-review rounded-pill fs-5 py-2 px-4" type="button">write a review</button>
                        </div>
                    </div>
                    <div id="feedback-div" class="d-none">
                        <div class="bg-main text-white d-flex row rounded-top">
                            <div class="col-9 py-2 d-flex flex-column justify-content-center">
                                <h2>Driver One</h2>
                                <div>
                                    <i class="fas fa-star me-2"></i>
                                    <i class="fas fa-star me-2"></i>
                                    <i class="fas fa-star me-2"></i>
                                    <i class="fas fa-star me-2"></i>
                                    <i class="fas fa-star me-2"></i>
                                </div>
                            </div>
                            <div class="col-3 text-center py-2 d-flex align-items-center">
                                <img class="rounded-circle bg-white" src={{asset("assets/img/client-5.jpg")}} style="width: 100px;height: 100px;">
                            </div>
                        </div>
                        <div class="py-2">
                            <div>
                                <p>
                                    <span class="me-1">The trip from</span>
                                    <span class="me-1 pickup-span fw-bold"></span>
                                    <span class="me-1">to</span>
                                    <span class="me-1 fw-bold">{{$address->name}}</span>
                                    <span></span>
                                    <span>{{$trip->distance}}</span>
                                    <span class="me-1">km</span>
                                    {{-- <span class="me-1">km</span> --}}
                                    <span class="me-1">took</span>
                                    <span class="me-1">{{$trip->final_duration}}</span>
                                    <span>minutes to arrive</span>
                                </p>
                            </div>
                            <form id="taxi-review-form" method="POST" action="{{ url('user/trip/'.$trip->id.'/post_review') }}">
                                @csrf
                                <div class="mb-4">
                                    <label class="form-label" for="feedback-input">feedback</label>
                                    <textarea class="form-control" id="feedback-input" name="message" placeholder="Write a Review" rows="5" required=""></textarea>
                                </div>
                                <div>
                                    <button class="btn btn-main rounded-pill fs-5 py-2 px-4" type="submit">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div id='map' style='width: 100%; height: 70vh;'></div>
                <div class="d-none">
                    <div id="driverMarker">
                        <img src="{{asset('user/assets/img/topview_car.png')}}" alt="Marker Image" style="height: 50px; width: 30px">
                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('components.new.user.footer')
    <script src={{asset("user/assets/js/trip_progress.js")}}>
    </script>
</body>

</html>
