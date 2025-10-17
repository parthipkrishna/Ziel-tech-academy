<div class="navbar-custom">
    <div class="topbar container-fluid">
        <div class="d-flex align-items-center gap-lg-2 gap-1">

            <!-- Topbar Brand Logo -->
            <div class="logo-topbar">
                <!-- Logo light -->
                <a href="{{ route('lms.dashboard.home') }}" class="logo-light">
                    <span class="logo-lg">
                        <img src="{{ asset('dashboard/logo/ziel-logo-2.png') }}" alt="logo">
                    </span>
                    <span class="logo-sm">
                        <img src="{{ asset('dashboard/logo/ziel-logo-2.png') }}" alt="small logo">
                    </span>
                </a>

                <!-- Logo Dark -->
                <a href="{{ route('lms.dashboard.home') }}" class="logo-dark">
                    <span class="logo-lg">
                        <img src="{{ asset('dashboard/logo/ziel-logo-2.png') }}" alt="dark logo">
                    </span>
                    <span class="logo-sm">
                        <img src="{{ asset('dashboard/logo/ziel-logo-2.png') }}" alt="small logo">
                    </span>
                </a>
            </div>

            <!-- Sidebar Menu Toggle Button -->
            <button class="button-toggle-menu">
                <i class="mdi mdi-menu"></i>
            </button>

            <!-- Horizontal Menu Toggle Button -->
            <button class="navbar-toggle" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <div class="lines">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>
        </div>

        <ul class="topbar-menu d-flex align-items-center gap-3">
            <li class="dropdown">
                <a class="nav-link dropdown-toggle arrow-none nav-user px-2" data-bs-toggle="dropdown" href="#"
                    role="button" aria-haspopup="false" aria-expanded="false">
                    <span class="account-user-avatar">
                        <img src="{{ !empty( auth()->user()->profile_image) ? env('STORAGE_URL') . '/' . str_replace('public/', '', auth()->user()->profile_image) : asset('dashboard/assets/images/avathar.png') }}"
                            alt="admin-image" width="40" height="40" class="rounded-circle">
                    </span>

                    <span class="d-lg-flex flex-column gap-1 d-none">
                        <h5 class="my-0">{{ auth()->user()->name }}</h5>
                        <h5 class="my-0">{{ auth()->user()->roles->pluck('role_name')->join(', ') }}</h5>
                        {{-- <h6 class="my-0 fw-normal">Admin</h6> --}}
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown">
                    <!-- item-->
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div>

                    <!-- item-->
                    <a href="{{ route('admin.profile') }}" class="dropdown-item">
                        <i class="mdi mdi-account-circle me-1"></i>
                        <span>My Account</span>
                    </a>

                    <!-- item-->
                    <form action="{{ route('lms.logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item border-0 bg-transparent w-100 text-start">
                            <i class="mdi mdi-logout me-1"></i>
                            <span>Logout</span>
                        </button>
                    </form>

                </div>
            </li>
        </ul>
    </div>
</div>