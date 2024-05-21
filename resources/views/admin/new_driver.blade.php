<!DOCTYPE html>
<html lang="en">

<?php
    $active_page = 'Add New Driver';
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
                <h3>Add A New Driver</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Add a New Driver</h4>
                                    </div>
                                    <div class="card-body">
                                        <form class="form" method="POST" action="{{url('admin/post_new_driver')}}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="col-12">
                                                    <div>
                                                        <img id="preview">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="color-main" for="image-input">Picture</label>
                                                        <input type="file" id="image-input" class="form-control" required autocomplete="off"
                                                            placeholder="Picture" name="picture">

                                                        <script>
                                                            var imageinput = document.getElementById("image-input");
                                                            var preview = document.getElementById("preview");

                                                            imageinput.addEventListener("change", function(event){
                                                                if(event.target.files.lenght == 0) {
                                                                    return;
                                                                }
                                                                var tempUrl = URL.createObjectURL(event.target.files[0]);
                                                                preview.setAttribute("src",tempUrl);
                                                                var style = "max-width:100%; height:30vh; border-radius:10px;";
                                                                preview.setAttribute("style", style);
                                                            })
                                                        </script>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label class="color-main" for="first-name-column">First Name</label>
                                                        <input type="text" id="first-name-column" class="form-control" required autocomplete="off"
                                                            placeholder="First Name" name="first_name">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label class="color-main" for="last-name-column">Last Name</label>
                                                        <input type="text" id="last-name-column" class="form-control" required autocomplete="off"
                                                            placeholder="Last Name" name="last_name">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label class="color-main" for="phone-column">Phone</label>
                                                        <input type="text" id="phone-column" class="form-control" required autocomplete="off"
                                                            placeholder="Phone Number" name="phone_number">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label class="color-main" for="email-floating">Email</label>
                                                        <input type="text" id="email-floating" class="form-control" required autocomplete="off"
                                                            name="email" placeholder="Email">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label class="color-main" for="nation-id-column">Nation ID</label>
                                                        <input type="text" id="nation-id-column" class="form-control" required autocomplete="off"
                                                            name="national_id" placeholder="Nation ID">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label class="color-main" for="password-id-column">Password</label>
                                                        <input type="password" id="password-id-column" class="form-control" required autocomplete="off"
                                                            name="password" placeholder="Password">
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
