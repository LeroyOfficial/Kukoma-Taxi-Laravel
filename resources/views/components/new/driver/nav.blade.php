<nav class="fw-bold" style="border-bottom-width: 4px;border-bottom-color: var(--color-main);">
    <div class="row">
        <div class="col-10 col-lg-4">
            <div class="logo-div">
                <a class="d-flex align-items-center" href={{url("/driver/dashboard")}}>
                <img class="me-2" src={{asset("assets/img/icon.png")}}>
                    <h2>
                        <span class="k">Kukoma&nbsp;</span>
                        <span class="t">Taxi</span>
                    </h2>
                </a>
            </div>
        </div>
        <div class="nav-list d-none d-lg-flex col-8 align-items-center justify-content-end">
            {{-- <div class="d-flex align-items-center">
                <i class="fas fa-user-circle fs-1 me-2"></i>
                <h4 class="text-capitalize">Takwana</h4>
            </div> --}}
            <ul class="list-inline">
                <li class="list-inline-item">
                    <a href={{url("driver/dashboard")}}>Home</a>
                </li>
                <li class="list-inline-item">
                    <a href={{url("driver/about")}}>About</a>
                </li>
                <li class="list-inline-item">
                    <a href={{url("driver/cars")}}>Cars</a>
                </li>
                <li class="list-inline-item">
                    <a href={{url("driver/history")}}>My History</a>
                </li>
                <li class="list-inline-item">
                    <a class="btn btn-main rounded-pill" role="button" href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >Logout</a>
                    <form id="logout-form" hidden="" method="POST" action="{{route('logout')}}">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
        <div class="col-2 d-flex align-items-center justify-content-end d-lg-none">
            <div class="dropdown">
                <button class="btn" aria-expanded="true" data-bs-toggle="dropdown" type="button">
                    <i class="fas fa-list-ul color-main fs-4"></i>
                </button>
                <div class="dropdown-menu" data-bs-popper="none">
                    <a class="dropdown-item" href={{url("driver/dashboard")}}>Home</a>
                    <a class="dropdown-item" href={{url("driver/about")}}>About</a>
                    <a class="dropdown-item" href={{url("driver/cars")}}>Cars</a>
                    <a class="dropdown-item" href={{url("driver/booking")}}>Book a Taxi</a>
                    <a class="dropdown-item" href={{url("driver/history")}}>My History</a>
                    <a class="dropdown-item" role="button" href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >Logout</a>
                </div>
            </div>
        </div>
    </div>
</nav>
