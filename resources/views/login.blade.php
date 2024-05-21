<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

@include('components.new.css')

<body>
    @include('components.new.nav')

    <div id="page-hero" class="page-hero text-center text-white d-flex align-items-center justify-content-center">
        <div>
            <span class="text-capitalize">
            <a href="index.html">Home</a>
            <span class="ms-1 color-main">// login</span>
        </span>
            <h1 class="text-uppercase fw-bold">login</h1>
        </div>
    </div>
    <div class="section">
        <div>
            <div class="text-center py-4">
                <i class="far fa-user color-main fs-1">k</i>
                <h2 class="text-capitalize">login</h2>
            </div>
            <form>
                <div class="row row-cols-1 row-cols-md-2 justify-content-center py-4">
                    <div class="mb-4">
                        <label class="form-label text-capitalize fw-bold" for="email">Email Address</label>
                        <input class="form-control" type="email" id="email" required="" name="email" placeholder="Email Address">
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-capitalize fw-bold" id="password" for="password">Password</label>
                        <input class="form-control" type="password" required="" name="password" placeholder="Password">
                    </div>
                </div>
                <div class="text-center py-4">
                    <button class="btn btn-main fs-5 text-uppercase rounded-pill py-2 px-5" type="submit">Login</button>
                </div>
            </form>
        </div>
    </div>

    @include('components.new.footer')
</body>

</html>
