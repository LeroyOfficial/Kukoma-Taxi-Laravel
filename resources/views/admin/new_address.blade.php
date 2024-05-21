<!DOCTYPE html>
<html lang="en">

<?php
    $active_page = 'Add New Address';
?>

<head>
    @include('components.new.admin.css')
</head>

<body>
    <div id="app">
        @include('components.new.admin.sidebar')

        <div id="main">
            @include('components.new.admin.header')

            <div class="page-heading">
                <h3>Add A New Address</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Add a New Address</h4>
                                    </div>
                                    <div class="card-body">
                                        <form class="form" method="POST" action="{{url('admin/post_new_address')}}">
                                            @csrf
                                            <div class="row">
                                                <div class="col-12 mb-4">
                                                    <h5>You dont know know the coords (Latitude and Longitude) of your address?</h5>
                                                    <h5>use <a target="blank" href="https://www.gps-coordinates.net/#map_canvas" class="color-main">www.gps-coordinates.net</a> to get the coords</h5>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label class="color-main" for="address-name-column">Address Name</label>
                                                        <input type="text" id="address-name-column" class="form-control" required autocomplete="off"
                                                            placeholder="Address Name" name="name">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label class="color-main" for="latitude-column">Address Latitude</label>
                                                        <input type="text" id="latitude-column" class="form-control" required autocomplete="off"
                                                            placeholder="Address Latitude" name="latitude">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label class="color-main" for="longitude-column">Address Longitude</label>
                                                        <input type="text" id="longitude-column" class="form-control" required autocomplete="off"
                                                            placeholder="Address Longitude" name="longitude">
                                                    </div>
                                                </div>
                                                <div class="col-12 d-flex justify-content-end">
                                                    <button type="submit"
                                                        class="btn btn-main me-1 mb-1">Submit</button>
                                                    <button type="reset"
                                                        class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                                </div>
                                            </div>
                                        </form>
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
