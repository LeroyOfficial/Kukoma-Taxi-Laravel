<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

@php
    $page_title = 'About';
@endphp
@include('components.new.css')

<body>
    @include('components.new.nav')

    <div id="page-hero" class="page-hero text-center text-white d-flex align-items-center justify-content-center">
        <div><span class="text-capitalize"><a href="index.html">Home</a><span class="ms-1 color-main">// About</span></span>
            <h1 class="text-uppercase fw-bold">About</h1>
        </div>
    </div>
    <div class="section"></div>

    @include('components.new.footer')
</body>

</html>
