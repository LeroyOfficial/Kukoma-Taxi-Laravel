<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

@php
    $page_title = 'Home';
@endphp
@include('components.new.css')

<body>
    @include('components.new.nav')

    <div id="hero" class="hero d-flex flex-column justify-content-between">
        <div>

        </div>
        <div class="main-row row mb-2 py-2">
            <div class="col-md-6 p-2">
                <div class="bg-grey p-5 rounded">
                    <div class="text-center text-uppercase fw-bold mb-4">
                        <h3 class="fw-bold">
                            <span class="k">Get Taxi&nbsp;</span>
                            <span class="t">Online</span>
                        </h3>
                    </div>
                    <div id="car-type-list" class="car-type-list text-uppercase row row-cols-2 row-cols-lg-4 justify-content-center mb-4">
                        <div class="p-2">
                            <a class="btn selected d-flex flex-column justify-content-center fw-bold" role="button">
                            <img src={{asset("assets/img/icon-car-1.png")}}>
                            <span>standard</span>
                        </a>
                        </div>
                        <div class="p-2">
                            <a class="btn d-flex flex-column justify-content-center fw-bold" role="button">
                            <img src={{asset("assets/img/icon-car-2.png")}}>
                            <span>business</span>
                        </a>
                        </div>
                        <div class="p-2">
                            <a class="btn d-flex flex-column justify-content-center fw-bold" role="button">
                            <img src={{asset("assets/img/icon-car-3.png")}}>
                            <span class="text-danger">vip</span>
                        </a>
                        </div>
                        <div class="p-2">
                            <a class="btn d-flex flex-column justify-content-center fw-bold" role="button">
                            <img src={{asset("assets/img/icon-car-4.png")}}>
                            <span>van</span>
                        </a>
                        </div>
                    </div>
                    <div id="hero-form">
                        <form>
                            <div class="row row-cols-1 row-cols-md-2">
                                <div class="mb-2 mb-md-4">
                                    <input class="form-control" type="text" name="from_address" placeholder="From Address" required="" minlength="2">
                                </div>
                                <div class="mb-2 mb-md-4">
                                    <input class="form-control" type="text" name="to" placeholder="To Address" required="" minlength="2">
                                </div>
                                <div class="mb-2 mb-md-4">
                                    <input class="form-control" type="tel" name="phome" placeholder="Phone Number" required="" minlength="2">
                                </div>
                                <div class="mb-2 mb-md-4">
                                    <input class="form-control" name="date and time" placeholder="Date And time" required="" minlength="2" min="Date();">
                                </div>
                            </div>
                            <div class="text-center">
                                <a href="{{url('user/booking')}}" role="button" class="btn btn-main fs-5 rounded-pill text-uppercase fw-bold py-2 px-5">Get Taxi</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6 d-none">

            </div>
        </div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 justify-content-center bg-main p-2 py-md-4 px-md-5 scrn-width text-center text-dark fw-bold fs-4">
            <div class="d-flex align-items-center mb-3 mb-lg-0">
                <i class="fas fa-phone bg-white p-3 rounded-circle me-2"></i>
                <span>+265993930231</span>
            </div>
            <div class="d-flex align-items-center mb-3 mb-lg-0">
                <i class="fas fa-taxi bg-white p-3 rounded-circle me-2"></i>
                <span>Get Taxi Online</span>
            </div>
            <div class="d-flex align-items-center mb-3 mb-lg-0">
                <i class="fas fa-map-marker-alt bg-white p-3 rounded-circle me-2"></i>
                <span>Nacit,Blantyre</span>
            </div>
            <div class="d-flex align-items-center mb-3 mb-lg-0">
                <a class="btn btn-hover-second text-uppercase fw-bold rounded-pill py-3 px-5 border border-2 border-dark" role="button" href={{url('/')}}>Our Tarrifs</a>
            </div>
        </div>
    </div>
    <div id="services" class="section">
        <div class="text-center text-uppercase mb-5">
            <span class="subheader">welcome</span>
            <h1 class="heading">our services</h1>
        </div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4">
            <div class="mb-4 mb-md-0 text-center">
                <img class="mb-3" src={{asset("assets/img/services-1.png")}}>
                <h6 class="mb-3 fw-bold">RAPID CITY TRANSFER</h6>
                <p class="fs-6">We will bring you quickly and comfortably to anywhere in your city</p>
            </div>
            <div class="mb-4 mb-md-0 text-center">
                <img class="mb-3" src={{asset("assets/img/services-1.png")}}>
                <h6 class="mb-3 fw-bold">RAPID CITY TRANSFER</h6>
                <p class="fs-6">We will bring you quickly and comfortably to anywhere in your city</p>
            </div>
            <div class="mb-4 mb-md-0 text-center">
                <img class="mb-3" src={{asset("assets/img/services-1.png")}}>
                <h6 class="mb-3 fw-bold">RAPID CITY TRANSFER</h6>
                <p class="fs-6">We will bring you quickly and comfortably to anywhere in your city</p>
            </div>
            <div class="mb-4 mb-md-0 text-center">
                <img class="mb-3" src={{asset("assets/img/services-1.png")}}>
                <h6 class="mb-3 fw-bold">RAPID CITY TRANSFER</h6>
                <p class="fs-6">We will bring you quickly and comfortably to anywhere in your city</p>
            </div>
        </div>
    </div>
    <div id="tariffs" class="section bg-grey">
        <div class="text-center text-uppercase mb-5">
            <span class="subheader">see our</span>
            <h1 class="heading">tariffs</h1>
        </div>
        <div class="tarifff-list row row-cols-1 row-cols-sm-2 row-cols-lg-4">
            <div class="p-3">
                <div class="bg-white py-5 px-3 rounded border border-1 border-secondary text-center">
                    <img class="mb-4" src={{asset("assets/img/tariff-1.png")}}>
                    <h2 class="text-uppercase mb-3">Standard</h2>
                    <p class="mb-4">Standard sedan for a drive around the city at your service</p>
                    <h1 class="fw-bold">$2 /km</h1>
                </div>
            </div>
            <div class="p-3">
                <div class="bg-white py-5 px-3 rounded border border-1 border-secondary text-center">
                    <img class="mb-4" src={{asset("assets/img/tariff-2.png")}}>
                    <h2 class="text-uppercase mb-3">business</h2>
                    <p class="mb-4">Standard sedan for a drive around the city at your service</p>
                    <h1 class="fw-bold">$2 /km</h1>
                </div>
            </div>
            <div class="p-3">
                <div class="bg-white py-5 px-3 rounded border border-1 border-secondary text-center">
                    <img class="mb-4" src={{asset("assets/img/tariff-3.png")}}>
                    <h2 class="text-uppercase mb-3">VIP</h2>
                    <p class="mb-4">Standard sedan for a drive around the city at your service</p>
                    <h1 class="fw-bold">$2 /km</h1>
                </div>
            </div>
            <div class="p-3">
                <div class="bg-white py-5 px-3 rounded border border-1 border-secondary text-center">
                    <img class="mb-4" src={{asset("assets/img/tariff-4.png")}} width="146" height="92"/>
                    <h2 class="text-uppercase mb-3">Van</h2>
                    <p class="mb-4">Standard sedan for a drive around the city at your service</p>
                    <h1 class="fw-bold">$2 /km</h1>
                </div>
            </div>
        </div>
    </div>
    <div id="get-the-app" class="text-white" style="background: url(&quot;assets/img/download-bg.jpg&quot;) center / cover no-repeat;">
        <div style="background: rgba(0,0,0,0.6);padding-bottom: 0px;padding-top: 10vh;padding-right: 5vw;padding-left: 5vw;">
            <div class="text-center text-uppercase mb-5">
                <span class="subheader">GET MORE BENEFITS</span>
                <h1 class="heading text-white">GET MORE BENEFITS</h1>
            </div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 justify-content-center">
                <div class="px-2 text-start">
                    <div class="d-flex mb-3">
                        <div>
                            <span class="bg-main p-2 fw-bold rounded-circle fs-4 text-dark">01.</span>
                        </div>
                        <div class="ps-4">
                            <h4 class="color-main text-uppercase">FAST BOOKING</h4>
                            <p>Nam ac ligula congue, interdum enim sit amet, fermentum nisi.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div>
                            <span class="bg-main p-2 fw-bold rounded-circle fs-4 text-dark">02.</span>
                        </div>
                        <div class="ps-4">
                            <h4 class="color-main text-uppercase">EASY TO USE</h4>
                            <p>Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <img src={{asset("assets/img/mobile.png")}}>
                </div>
                <div class="px-2 text-end">
                    <div class="d-flex mb-3">
                        <div class="pe-4">
                            <h4 class="color-main text-uppercase">GPS SEARCHING</h4>
                            <p>Ut elementum tincidunt erat vel ornare. Suspendisse ac felis non diam pretium.</p>
                        </div>
                        <div>
                            <span class="bg-main p-2 fw-bold rounded-circle fs-4 text-dark">03.</span>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="pe-4">
                            <h4 class="color-main text-uppercase">BONUSES FOR RIDE</h4>
                            <p>Phasellus l et porta tortor dignissim at. Pellentesque gravida tortor at euismod mollis.</p>
                        </div>
                        <div>
                            <span class="bg-main p-2 fw-bold rounded-circle fs-4 text-dark">04.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="for-drivers" class="section">
        <div class="col-md-6 text-start mb-5">
            <span class="subheader">FOR DRIVERS</span>
            <h1 class="heading mb-4">DO YOU WANT TO EARN WITH US?</h1>
            <p class="mb-5">Quisque sollicitudin feugiat risus, eu posuere ex euismod eu. Phasellus hendrerit, massa efficitur dapibus pulvinar, sapien eros sodales ante, euismod aliquet nulla metus a mauris.</p>
            <div class="row row-cols-1 row-cols-md-2 mb-5">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-check color-main me-3 fs-4"></i>
                    <span class="fw-bold">Luxury cars</span>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-check color-main me-3 fs-4"></i>
                    <span class="fw-bold">Fixed price</span>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-check color-main me-3 fs-4"></i>
                    <span class="fw-bold">No fee</span>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-check color-main me-3 fs-4"></i>
                    <span class="fw-bold">Luxury cars</span>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-check color-main me-3 fs-4"></i>
                    <span class="fw-bold">Luxury cars</span>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-check color-main me-3 fs-4"></i>
                    <span class="fw-bold">Luxury cars</span>
                </div>
            </div>
            <a class="btn btn-main py-2 px-5 fs-4 text-uppercase rounded-pill" role="button">Become a Driver</a>
        </div>
    </div>
    <div id="testimonies" class="section bg-grey">
        <div class="text-center text-uppercase mb-5">
            <span class="subheader">happy clients</span>
            <h1 class="heading">testimonials</h1>
        </div>
        <div class="tesstimony-list text-center row row-cols-1 row-cols-sm-2 row-cols-lg-3 justify-content-center py-4">
            <div class="p-4">
                <div class="px-3 pt-5 bg-white rounded d-flex flex-column justify-content-between">
                    <p>Quisque sollicitudin feugiat risus, eu posuere ex euismod eu. Phasellus hendrerit, massa efficitur dapibus pulvinar, sapien eros sodales ante, euismod aliquet nulla metus a mauris.</p>
                    <div style="margin-bottom: -20%;">
                        <h5>Takwana</h5>
                        <img class="rounded-circle mt-2 bg-white border-main" src={{asset("assets/img/client-2.jpg")}}>
                    </div>
                </div>
            </div>
            <div class="p-4">
                <div class="px-3 pt-5 bg-white rounded d-flex flex-column justify-content-between">
                    <p>Quisque sollicitudin feugiat risus, eu posuere ex euismod eu. Phasellus hendrerit, massa efficitur dapibus pulvinar, sapien eros sodales ante, euismod aliquet nulla metus a mauris.</p>
                    <div style="margin-bottom: -20%;">
                        <h5>Takwana</h5>
                        <img class="rounded-circle mt-2 bg-white border-main" src={{asset("assets/img/client-2.jpg")}}>
                    </div>
                </div>
            </div>
            <div class="p-4">
                <div class="px-3 pt-5 bg-white rounded d-flex flex-column justify-content-between">
                    <p>Quisque sollicitudin feugiat risus, eu posuere ex euismod eu. Phasellus hendrerit, massa efficitur dapibus pulvinar, sapien eros sodales ante, euismod aliquet nulla metus a mauris.</p>
                    <div style="margin-bottom: -20%;">
                        <h5>Takwana</h5>
                        <img class="rounded-circle mt-2 bg-white border-main" src={{asset("assets/img/client-2.jpg")}}>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.new.footer')

</body>

</html>
