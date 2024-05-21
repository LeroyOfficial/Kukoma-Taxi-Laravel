<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="{{url('admin/dashboard')}}">
                        <img src="{{asset('admin/assets/images/logo.png')}}" alt="Logo" style="height: 50px;" srcset="">
                        <span class="fw-bold fs-3">
                            <span style="color: var(--color-main);">Kukoma</span>
                            <span style="color: black">Taxi</span>
                        </span>
                    </a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block">
                        <i class="bi bi-x bi-middle"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item @if ($active_page == 'Dashboard') active @endif">
                    <a href="{{url('admin/dashboard')}}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item @if ($active_page == 'Drivers' || $active_page == 'Driver' || $active_page == 'Add New Driver') active @endif">
                    <a href="{{url('admin/drivers')}}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Drivers</span>
                    </a>
                </li>

                <li class="sidebar-item @if ($active_page == 'Users' || $active_page == 'User') active @endif">
                    <a href="{{url('admin/users')}}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Users</span>
                    </a>
                </li>

                <li class="sidebar-item @if ($active_page == 'Cars' || $active_page == 'Car') active || $active_page == 'Add New Car') active @endif">
                    <a href="{{url('admin/cars')}}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Cars</span>
                    </a>
                </li>

                <li class="sidebar-item @if ($active_page == 'Trips' || $active_page == 'Trip') active @endif">
                    <a href="{{url('admin/trips')}}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Trips</span>
                    </a>
                </li>

                <li class="sidebar-item @if ($active_page == 'Addresses' || $active_page == 'Add New Address')) active @endif">
                    <a href="{{url('admin/addresses')}}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Addresses</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a role="button" href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Logout</span>
                    </a>

                    <form id="logout-form" hidden="" method="POST" action="{{route('logout')}}">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
        <button class="sidebar-toggler btn x">
            <i data-feather="x"></i>
        </button>
    </div>
</div>
