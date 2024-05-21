<!DOCTYPE html>
<html lang="en">

<?php $active_page = 'Dashboard'; ?>

<head>
    @include('components.new.admin.css')
</head>

<body>
    <div id="app">
        @include('components.new.admin.sidebar')

        <div id="main">
            <div class="page-heading">
                <h3>Admin Panel</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12 col-lg-9">
                        <div class="row">

                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon purple">
                                                    <i class="iconly-boldProfile"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Users</h6>
                                                <h6 class="font-extrabold mb-0">{{$user_count}}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon green">
                                                    <i class="iconly-boldProfile"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Drivers</h6>
                                                <h6 class="font-extrabold mb-0">{{$driver_count}}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon green">
                                                    <i class="iconly-boldAdd-User"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold">Cars</h6>
                                                <h6 class="font-extrabold mb-0">{{$car_count}}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-3 py-4-5">
                                        <div class="row">
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
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Latest Trips</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-lg">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Pickup</th>
                                                        <th>Destination</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($recent_trips as $trip)
                                                        <tr>
                                                            <td class="col-3">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar avatar-md">
                                                                        <img src="{{asset('admin/assets/images/faces/5.jpg')}}">
                                                                    </div>
                                                                    <p class="font-bold ms-3 mb-0">{{$user->where('id',$trip->id)->value('name')}}</p>
                                                                </div>
                                                            </td>
                                                            <?php $pickupData = json_decode($trip->pickup, true); ?>
                                                            <td class="col-auto">
                                                                <p class=" mb-0">{{$pickupData['name']}}</p>
                                                            </td>
                                                            <td class="col-auto">
                                                                <p class=" mb-0">{{$address->where('id',$trip->destination)->value('name')}}</p>
                                                            </td>
                                                            <td class="col-auto">
                                                                <p class=" mb-0">{{$trip->created_at->format('d M Y')}}</p>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="row justify-content-end">
                                            <div class="col-4 px-4">
                                                <a href="{{url('admin/trips')}}" role="button" class='btn btn-hover-main btn-block btn-md btn-light-primary font-bold mt-3'>view all</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <div class="card">
                            <div class="card-body py-4 px-5">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xl">
                                        <img src="{{asset('admin/assets/images/faces/1.jpg')}}" alt="Face 1">
                                    </div>
                                    <div class="ms-3 name">
                                        <h5 class="font-bold">{{Auth::user()->name}}</h5>
                                        <h6 class="text-muted mb-0">Admin</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4>Drivers</h4>
                            </div>
                            <div class="card-content pb-4">
                                @if ($driver_count > 0)
                                    @foreach ($drivers as $driver)
                                        <div class="recent-message d-flex px-4 py-3">
                                            <div class="avatar avatar-lg">
                                                <img src="{{asset('Driver_Pics/'.$driver->picture_url)}}">
                                            </div>
                                            <div class="name ms-4">
                                                <h5 class="mb-1">{{$driver->first_name}} {{$driver->last_name}}</h5>
                                                <h6 class="text-muted mb-0">{{$driver->status}}</h6>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="recent-message d-flex px-4 py-3">
                                        <div class="name ms-4">
                                            <h5 class="mb-1">No Drivers Available</h5>
                                        </div>
                                    </div>
                                @endif

                                @if ($driver_count > 0)
                                    <div class="px-4">
                                        <a href="{{url('admin/drivers')}}" role="button" class='btn btn-block btn-md btn-light-primary font-bold mt-3'>view all</a>
                                    </div>
                                @else
                                    <div class="px-4">
                                        <a href="{{url('admin/new_driver')}}" role="button" class='btn btn-block btn-md btn-light-primary font-bold mt-3'>add a driver</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            @include('components.new.admin.footer')

</body>

</html>
