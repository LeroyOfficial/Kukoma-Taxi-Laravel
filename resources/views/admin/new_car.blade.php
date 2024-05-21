<!DOCTYPE html>
<html lang="en">

<?php
    $active_page = 'Add New Car';
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
                <h3>Add A New Car</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Add a New Car</h4>
                                    </div>
                                    <div class="card-body">
                                        <form class="form" method="POST" action="{{url('admin/post_new_car')}}" enctype="multipart/form-data">
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
                                                        <label class="color-main" for="brand-column">Brand eg Toyota</label>
                                                        <input type="text" id="brand-column" class="form-control" required autocomplete="off"
                                                            placeholder="Brand" name="brand">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label class="color-main" for="model-column">Model eg Axio</label>
                                                        <input type="text" id="model-column" class="form-control" required autocomplete="off"
                                                            placeholder="Model" name="model">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label class="color-main" for="type-column">Car Type</label>
                                                        <fieldset class="form-group">
                                                            <select class="form-select" name="type" id="basicSelect">
                                                                <option></option>
                                                                @foreach ($categories as $category)
                                                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label class="color-main" for="number-plate-column">Number plate eg BS 1234</label>
                                                        <input type="text" id="number-plate-column" class="form-control" required autocomplete="off"
                                                            placeholder="Number Plate" name="number_plate">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label class="color-main" for="color-input">Color</label>
                                                        <input type="text" id="color-input" class="form-control" required autocomplete="off"
                                                            name="color" placeholder="Color">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-group">
                                                        <label class="color-main" for="capacity-column">Capacity</label>
                                                        <input type="number" id="capacity-column" class="form-control" required autocomplete="off"
                                                            name="capacity" placeholder="Capacity">
                                                    </div>
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
