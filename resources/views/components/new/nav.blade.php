<nav class="fw-bold" style="border-bottom-width: 4px;border-bottom-color: var(--color-main);">
    <div class="row">
        <div class="col-10 col-lg-4">
            <div class="logo-div">
                <a class="d-flex align-items-center" href={{url("/")}}>
                <img class="me-2" src="assets/img/icon.png">
                    <h2>
                        <span class="k">Kukoma&nbsp;</span>
                        <span class="t">Taxi</span>
                    </h2>
                </a>
            </div>
        </div>
        <div class="nav-list d-none d-lg-flex col-lg-8 align-items-center justify-content-end">
            <ul class="list-inline">
                <li class="list-inline-item">
                    <a href={{url("/")}}>Home</a>
                </li>
                <li class="list-inline-item">
                    <a href={{url("/about")}}>About</a>
                </li>
                <li class="list-inline-item">
                    <a href={{url("/cars")}}>Cars</a>
                </li>
                <li class="list-inline-item">
                    <a href={{url("/booking")}}>Book a Taxi</a>
                </li>
                @auth
                    <li class="list-inline-item">
                        <a class="btn btn-main rounded-pill" role="button" href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >Logout</a>
                        <form id="logout-form" hidden="" method="POST" action="{{route('logout')}}">
                            @csrf
                        </form>
                    </li>
                @else
                    <li class="list-inline-item">
                        <a class="btn btn-main rounded-pill" role="button" href={{url("/login")}}>Login</a>
                    </li>
                    <li class="list-inline-item">
                        <a class="btn btn-second rounded-pill" role="button" href={{url("/register")}}>Signup</a>
                    </li>
                @endauth
            </ul>
        </div>
        <div class="col-2 d-flex align-items-center justify-content-end d-lg-none">
            <div class="dropdown">
                <button class="btn" aria-expanded="true" data-bs-toggle="dropdown" type="button">
                    <i class="fas fa-list-ul color-main fs-4"></i>
                </button>
                <div class="dropdown-menu" data-bs-popper="none">
                    <a class="dropdown-item" href={{url("user/dashboard")}}>Home</a>
                    <a class="dropdown-item" href={{url("about")}}>About</a>
                    <a class="dropdown-item" href={{url("cars")}}>Cars</a>
                    <a class="dropdown-item" href={{url("user/booking")}}>Book a Taxi</a>
                    <a class="dropdown-item" href={{url("user/history")}}>My History</a>
                    <a class="dropdown-item" href={{url("/login")}}>Login</a>
                    <a class="dropdown-item" href={{url("/register")}}>Signup</a>
                </div>
            </div>
        </div>
    </div>
</nav>
