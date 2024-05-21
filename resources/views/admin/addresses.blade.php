<!DOCTYPE html>
<html lang="en">

<?php $active_page = 'Addresses'; ?>

<head>
    @include('components.new.admin.css')
</head>

<body>
    <div id="app">
        @include('components.new.admin.sidebar')
        <div id="main">
            @include('components.new.admin.header')

            <div class="page-heading">
                <h3>Addresses</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between">
                                            <h4>List of Addresses</h4>

                                            <a href="{{url('admin/new_address')}}" role="button" class="btn btn-main fw-bold">Add a new address</a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @if ($address_count > 0)
                                            @if (session()->has('message'))
                                                @include('components.new.admin.alert')
                                            @endif
                                            <div class="table-responsive">
                                                <table class="table" id="table1">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Latitude</th>
                                                            <th>Longitude</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>

                                                        @foreach ($addresses as $address)
                                                            <tr>
                                                                <td class="col-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <p class="font-bold ms-3 mb-0">{{$address->name}}</p>
                                                                    </div>
                                                                </td>
                                                                <td class="col-auto">
                                                                    <p class="mb-0">{{$address->latitude}}</p>
                                                                </td>
                                                                <td class="col-auto">
                                                                    <p class="mb-0">{{$address->longitude}}</p>
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <h3 class="text-center">
                                                It seems that there are no addresses available
                                            <br>
                                                <a href={{url('admin/new_address')}} class="color-main">click here to add an address</a>
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
