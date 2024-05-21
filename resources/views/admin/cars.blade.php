<!DOCTYPE html>
<html lang="en">

<?php $active_page = 'Cars'; ?>

<head>
    @include('components.new.admin.css')
</head>

<body>
    <div id="app">
        @include('components.new.admin.sidebar')
        <div id="main">
            @include('components.new.admin.header')

            <div class="page-heading">
                <h3>Cars</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between">
                                            <h4>List of Cars</h4>

                                            <a href="{{url('admin/new_car')}}" role="button" class="btn btn-main fw-bold">Add a new car</a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                            @if ($car_count > 0)
                                                @if (session()->has('message'))
                                                    @include('components.new.admin.alert')
                                                @endif
                                                <div class="table-responsive">
                                                    <table class="table" id="table1">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Type</th>
                                                                <th>Number Plate</th>
                                                                <th>Color</th>
                                                                <th>Capacity</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($cars as $car)
                                                                <tr>
                                                                    <td class="col-3">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar avatar-md">
                                                                                <img src="{{asset('Car_Pics/'.$car->picture_url)}}">
                                                                            </div>
                                                                            <a href="{{url('admin/car/'.$car->id)}}" class="font-bold ms-3 mb-0">
                                                                                {{$car->brand}} {{$car->model}}
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                    <td class="col-auto">
                                                                        <p class=" mb-0">{{$category->where('id',$car->type)->value('name')}}</p>
                                                                    </td>
                                                                    <td class="col-auto">
                                                                        <p class=" mb-0">{{$car->number_plate}}</p>
                                                                    </td>
                                                                    <td class="col-auto">
                                                                        <p class=" mb-0 text-{{$car->color}}">{{$car->color}}</p>
                                                                    </td>
                                                                    <td class="col-auto">
                                                                        <p class=" mb-0">{{$car->capacity}}</p>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                            @else
                                                <h3 class="text-center">
                                                    It seems that there are no cars available
                                                <br>
                                                    <a href={{url('admin/new_car')}} class="color-main">click here to add a car</a>
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
