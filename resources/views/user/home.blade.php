<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    @php
        $page_title = 'Dashboard';
    @endphp
    @include('components.new.user.css')
</head>

<body>
    @include('components.new.user.nav')

    <div id="page-hero" class="page-hero text-center text-white d-flex align-items-center justify-content-center">
        <div>
            <span class="text-capitalize">
                <a href={{url('user/dashboard')}}>Home</a>
                <span class="ms-1 color-main">// home</span>
            </span>
            <h1 class="text-uppercase fw-bold">home</h1>
        </div>
    </div>

    <div class="section" style="padding: 0px;">
        <div class="bg-main color-second px-4 py-4">
            <h1>Welcome {{Auth::user()->name}}</h1>
        </div>

        <div class="px-4 py-4 bg-grey" style="min-height: 100vh;">
            <h3>Recent Trips</h3>
            <div id="recent-trip-list" class="py-4 pe-4 fw-bold fs-5" style="overflow-y: auto;max-height: 80vh;">

                @if ($trips->count() > 0)
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
                                    driven by
                                    {{$driver->where('id',$trip->driver_id)->value('first_name')}}
                                    {{$driver->where('id',$trip->driver_id)->value('last_name')}}
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
                            <div class="col-sm-6 col-md-6 col-lg-3">
                                <p>status: {{$trip->status}}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <h3 class="text-center">
                        It seems that you havent taken any trips with us
                    <br>
                        <a href={{url('user/booking')}} class="color-main">click here to book a taxi</a>
                    </h3>
                @endif

            </div>
        </div>
    </div>

    @include('components.new.user.footer')
</body>

</html>
