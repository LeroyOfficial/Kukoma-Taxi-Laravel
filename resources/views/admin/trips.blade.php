<!DOCTYPE html>
<html lang="en">

<?php $active_page = 'Trips'; ?>

<head>
    @include('components.new.admin.css')
</head>

<body>
    <div id="app">
        @include('components.new.admin.sidebar')
        <div id="main">
            @include('components.new.admin.header')

            <div class="page-heading">
                <h3>Trips</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between">
                                            <h4>List of Trips</h4>
                                            <div class="dropdown show text-capitalize">
                                                <button class="btn dropdown-toggle btn-main" aria-expanded="true" data-bs-toggle="dropdown" type="button">Generate Report</button>
                                                <div class="dropdown-menu ms-3" data-bs-popper="none">
                                                    <a class="dropdown-item fw-bold btn-hover-main" href="{{url('admin/trip_history/generate_report/today')}}">for Today</a>
                                                    <a class="dropdown-item fw-bold btn-hover-main" href="{{url('admin/trip_history/generate_report/weekly')}}">For this week</a>
                                                    <a class="dropdown-item fw-bold btn-hover-main" href="{{url('admin/trip_history/generate_report/monthly')}}">for this month</a>
                                                    <a class="dropdown-item fw-bold btn-hover-main" href="{{url('admin/trip_history/generate_report/all')}}">Entire History</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @if ($trip_count > 0)
                                            <div class="table-responsive">
                                                <table class="table" id="table1">
                                                    <thead>
                                                        <tr>
                                                            <th>Customer</th>
                                                            <th>Driver</th>
                                                            <th>Car</th>
                                                            <th>Pickup</th>
                                                            <th>Destination</th>
                                                            <th>Distance</th>
                                                            <th>Duration</th>
                                                            <th>Price</th>
                                                            <th>Date</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach ($trips as $trip)
                                                            <tr>
                                                                <td class="col-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="avatar avatar-md">
                                                                            <img src="{{asset('admin/assets/images/faces/5.jpg')}}">
                                                                        </div>
                                                                        <a href="{{url('admin/user/'.$user->where('id',$trip->user_id)->value('id'))}}" class="font-bold ms-3 mb-0">{{$user->where('id',$trip->user_id)->value('name')}}</a>
                                                                    </div>
                                                                </td>
                                                                <td class="col-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="avatar avatar-md">
                                                                            <img src="{{asset('Driver_Pics/'.$driver->where('id',$trip->driver_id)->value('picture_url'))}}">
                                                                        </div>
                                                                        <a href="{{url('admin/driver/'.$driver->where('id',$trip->driver_id)->value('id'))}}" class="font-bold ms-3 mb-0">{{$driver->where('id',$trip->driver_id)->value('first_name')}} {{$driver->where('id',$trip->driver_id)->value('last_name')}}</a>
                                                                    </div>
                                                                </td>
                                                                <td class="col-auto">
                                                                    <p class="mb-0">{{$car->where('id',$trip->car_id)->value('brand')}} {{$car->where('id',$trip->car_id)->value('model')}} ({{$car->where('id',$trip->car_id)->value('number_plate')}})</p>
                                                                </td>
                                                                <?php $pickupData = json_decode($trip->pickup, true); ?>
                                                                <td class="col-auto">
                                                                    <p class="mb-0">{{$pickupData['name']}}</p>
                                                                </td>
                                                                <td class="col-auto">
                                                                    <p class="mb-0">{{$address->where('id',$trip->destination)->value('name')}}</p>
                                                                </td>
                                                                <td class="col-auto">
                                                                    <p class="mb-0">{{$trip->distance}} km</p>
                                                                </td>
                                                                <td class="col-auto">
                                                                    <p class="mb-0">{{$trip->duration}}min</p>
                                                                </td>
                                                                <td class="col-auto">
                                                                    <p class="mb-0">K{{$trip->price}}</p>
                                                                </td>
                                                                <td class="col-auto">
                                                                    <p class="mb-0">{{$trip->created_at->format('d M Y')}}</p>
                                                                </td>
                                                                <td class="col-auto">
                                                                    <span class="badge
                                                                        @if ($trip->status == 'pending') bg-main
                                                                        @elseif ($trip->status == 'waiting for driver') bg-primary
                                                                        @elseif ($trip->status == 'driver is coming') bg-info
                                                                        @elseif ($trip->status == 'arrived at pickup point') bg-info
                                                                        @elseif ($trip->status == 'going to destination') bg-info
                                                                        @elseif ($trip->status == 'arrived at destination') bg-info
                                                                        @elseif ($trip->status == 'completed') bg-successs
                                                                        @elseif ($trip->status == 'cancelled') bg-danger
                                                                        @else
                                                                            bg-primary
                                                                        @endif

                                                                        ">
                                                                        {{$trip->status}}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>

                                                    <thead>
                                                        <tr>
                                                            <th>Total</th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th>
                                                                <span class="d-flex">
                                                                    <p class="mb-0">K{{$total_revenue}}</p>
                                                                </span>
                                                            </th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        @else
                                            <h3 class="text-center">
                                                It seems that there are no trips available
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
