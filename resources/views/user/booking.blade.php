<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    @php
        $page_title = 'Taxi Booking';
    @endphp
    @include('components.new.user.css')
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>
</head>

{{-- @include('components.new.user.css') --}}

<body>
    @include('components.new.user.nav')

    <div id="page-hero" class="page-hero text-center text-white d-flex align-items-center justify-content-center">
        <div>
            <span class="text-capitalize">
            <a href={{url("user/dashboard")}}>Home</a>
            <span class="ms-1 color-main">// Booking</span>
        </span>
            <h1 class="text-uppercase fw-bold">booking</h1>
        </div>
    </div>
    <div class="section">
        <h2 class="mb-4">Where do you want to go?</h2>
        <div class="row row-cols-1 row-cols-md-2 flex-column-reverse flex-md-row">
            <div>
                <form id="taxi-booking-form" method="POST" action="{{ url('user/post_new_booking') }}">
                    @csrf
                    <div id="directions">
                        <div class="mb-4">
                            <div id="user-location"></div>

                            <div class="mb-4">
                                <label class="form-label from-span fw-bold text-capitalize" for="pickup">from Current Location</label>
                                <input class="form-control d-none" type="text" value="Current Address" id="pickup" name="pickup" placeholder="Select Pick Up" required="">
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-capitalize" for="destination">to</label>
                                <input class="form-control" type="text" id="destination" name="destination" placeholder="Select Destination" required="">
                            </div>

                            <div id="search-result" class="d-none mb-2">
                            </div>

                            <div id="confirm-address-btn-div" class="d-none mb-4">
                                <button id="confirm-address-btn" class="btn btn-main rounded-pill fs-6 py-2 px-4" type="button">Confirm Address</button>
                            </div>

                            <div id="distance-duration-div" class="mb-4 fs-5 d-none fw-bold d-flex justify-content-around">
                                <span>
                                    <span>Distance: </span>
                                    <span id="distance-span"></span>
                                    <span>km</span>
                                </span>

                                <span>
                                    <span>Duration: </span>
                                    <span id="duration-span"></span>
                                    <span>minutes</span>
                                </span>
                            </div>

                        </div>

                        <div id="select-car-div" class="mb-4 d-none">
                            <h4>Select Car Type</h4>
                            <span id="car-type-not-selected" class="text-danger fw-bold d-none mb-1">Please select the car type below*</span>

                            <div id="car-type-list" class="car-type-list text-uppercase row row-cols-2 row-cols-lg-4 justify-content-center mb-4">
                                @foreach ($car_categories as $category)
                                    <div class="p-2">
                                        <a class="btn car-btn d-flex flex-column justify-content-center fw-bold" role="button">
                                            <img src="{{asset($category->picture_url)}}">
                                            <span class="car-type-name">{{$category->name}}</span>
                                            <span>
                                            <span>K</span>
                                            <span class="me-1 car-type-price">{{$category->price}}</span>
                                                <span>/km</span>
                                            </span>
                                        </a>
                                    </div>
                                @endforeach

                            </div>

                            {{-- <div class="car-list row row-cols-2 row-cols-lg-4">
                                <div class="">
                                    <img src="" alt="" class="">
                                    <span class="fs-6">Car 1</span>
                                </div>
                            </div> --}}
                        </div>

                        <div id="price-div" class="mb-4 fs-5 d-none fw-bold">
                            <span>
                                <span>Price: K</span>
                                <span id="price-span" class="price-span-value"></span>
                            </span>
                        </div>

                        <input class="form-control" hidden type="text" id="car-type-name-input" name="car_type_name" >

                        <input class="form-control" hidden type="text" id="car-type-price-input" name="car_type_price" >

                        <input class="form-control" hidden type="text" id="distance-input" name="distance" >

                        <input class="form-control" hidden type="text" id="duration-input" name="duration" >

                        <input class="form-control" hidden type="text" id="price-input" name="price" >

                        <input class="form-control" hidden type="text" id="closest_driver_id-input" name="closest_driver_id" >

                        <div id="next-btn-div" class="d-none">
                            <button class="btn btn-main next-btn rounded-pill fs-6 py-2 px-4" type="button">next</button>
                        </div>
                    </div>

                    <div id="payment" class="d-none">
                        <div class="mb-2">
                            <div class="mb-2">
                                <p class="fw-bold">
                                    <span class="me-1">Pay for your trip from</span>
                                    <span class="me-1 color-main from-span"></span>
                                    <span class="me-1">to</span>
                                    <span id="to-span" class="me-1 text-success"></span>
                                    <span class="me-1">which cost</span>
                                    <span id="price-span">
                                    <span>K</span>
                                    <span class="price-span-value"></span>
                                </span>
                                </p>
                                <div class="mb-4">
                                    <h4>Payment Method</h4>
                                    <div class="px-1">
                                        <img src="assets/img/airtel_money.png" style="height: 10vh;">
                                        <img src="assets/img/tnm_mpamba.png" style="height: 10vh;">
                                    </div>
                                </div>
                                <div id="mobile-div" class="mb-4">
                                    <p>
                                        <span class="me-1">Please send</span>
                                        <span class="fw-bold text-capitalize me-1">
                                        <span>K</span>
                                        <span class="price-span-value"></span>
                                    </span>
                                        <span class="me-1">to</span>
                                        <span class="me-1 fw-bold">0993930231</span>
                                        <span class="me-1">(Airtel Money)</span>
                                        <span class="me-1">or to</span>
                                        <span class="me-1 fw-bold">0885879301</span>
                                        <span class="me-1">(TNM mpamba)</span>
                                        <span class="me-1">Then enter the transID below</span>
                                    </p>
                                    <div>
                                        <label class="form-label" for="transid">Trans ID</label>
                                        <input class="form-control" type="text" id="transid" name="transId" placeholder="Enter Trans ID" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-second back-btn me-2 rounded-pill fs-6 py-2 px-4" type="button">Back</button>
                            <button class="btn btn-main submit-btn rounded-pill fs-6 py-2 px-4" type="submit">Pay</button>
                        </div>
                    </div>
                </form>
            </div>
            <div>
                <div id='map' style='width: 100%; height: 70vh;'></div>
            </iframe>
            </div>
        </div>
    </div>

    @include('components.new.user.footer')
    <script src={{asset("user/assets/js/booking.js")}}>
    </script>

</body>

</html>
