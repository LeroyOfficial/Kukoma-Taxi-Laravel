<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    @php
        $page_title = 'Cars';
    @endphp
    @include('components.new.user.css')
</head>

<body>
    @include('components.new.user.nav')

    <div id="page-hero" class="page-hero text-center text-white d-flex align-items-center justify-content-center">
        <div>
            <span class="text-capitalize">
                <a href="{{url('user/dashboard')}}">Home</a>
                <span class="ms-1 color-main">// Cars</span>
            </span>
            <h1 class="text-uppercase fw-bold">cars</h1>
        </div>
    </div>
    <div class="section bg-grey">
        <h1 class="mb-4">Car List</h1>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4">
            @if ($car_count > 0)

                @foreach ($cars as $car)
                    <div id="car-1" class="mb-4">
                    <div class="rounded bg-white">
                        <img class="w-100 rounded-top" src="{{asset($car->picture_url)}}">
                        <div class="px-3 py-4 text-capitalize">
                            <h3 class="fw-bold mb-2">
                                {{$car->brand}}
                                {{$car->model}}
                            </h3>
                            <div class="row row-cols-1 row-cols-md-2 fw-bold mb-1">
                                <div class="mb-2">
                                    <i class="fas fa-chevron-right color-main me-2"></i>
                                    <span class="me-1">{{$car_categories->where('id',$car->id)->value('name')}}</span>
                                    <i class="fas fa-car"></i>
                                </div>
                                <div class="mb-2">
                                    <i class="fas fa-chevron-right color-main me-2"></i>
                                    <span class="me-1">{{$car->capacity}}</span>
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <h5 class="text-lowercase text-center">K{{$car_categories->where('id',$car->id)->value('price')}}/km</h5>
                        </div>
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

    @include('components.new.user.footer')
</body>

</html>

