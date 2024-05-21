<!DOCTYPE html>
<html lang="en">

<?php $active_page = 'User'; ?>

<head>
    @include('components.new.admin.css')
</head>

<body>
    <div id="app">
        @include('components.new.admin.sidebar')
        <div id="main">
            @include('components.new.admin.header')

            <div class="page-heading">
                <h3>Customer Details</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>{{$user->name}} Details</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-5">
                                            <div class="col-12 col-lg-3">
                                                <img src="{{asset('admin/assets/images/faces/5.jpg')}}" class="rounded-circle" style="max-height: 100px; max-width:100px" alt="{{$user->name}}'s image">
                                            </div>
                                            <div class="col-12 col-lg-7">
                                                <div class="row row-cols-md-2">
                                                    <div class="mb-3">
                                                        <p class="fw-bold m-0">Name</p>
                                                        <p class="m-0">{{$user->name}}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <p class="fw-bold m-0">Phone Number</p>
                                                        <p class="m-0">{{$user->phone}}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <p class="fw-bold m-0">Email</p>
                                                        <p class="m-0">{{$user->email}}</p>
                                                    </div>
                                                    <div class="mb-3">
                                                        <p class="fw-bold m-0">Date Joined</p>
                                                        <p class="m-0">{{$user->created_at->format('d M Y')}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($trip_count > 0)
                                            <div class="row">
                                                <div class="col-12 col-lg-9">
                                                    <div class="d-flex justify-content-between">
                                                        <h6>{{$user->name}} Trips</h6>

                                                        <a href="{{url('admin/user/'.$user->id.'/generate_report')}}" role="button" class="btn btn-main">Generate Report</a>
                                                    </div>
                                                    <div class="table-responsive">
                                                        <table class="table" id="table1">
                                                            <thead>
                                                                <tr>
                                                                    <th>Driver Name</th>
                                                                    <th>Pickup</th>
                                                                    <th>Destination</th>
                                                                    <th>Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                                @foreach ($trips as $trip)
                                                                    <tr>
                                                                        <td class="col-3">
                                                                            <div class="d-flex align-items-center">
                                                                                <div class="avatar avatar-md">
                                                                                    <img src="{{asset('Driver_Pics/'.$driver->where('id',$trip->driver_id)->value('picture_url'))}}">
                                                                                </div>
                                                                                <a href="{{url('admin/driver/'.$driver->where('id',$trip->driver_id)->value('id'))}}" class="font-bold ms-3 mb-0">{{$driver->where('id',$trip->driver_id)->value('first_name')}} {{$driver->where('id',$trip->driver_id)->value('last_name')}}</a>
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
                                                It seems that {{$user->name}} hasn't taken any trips yet
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
</body>

</html>
