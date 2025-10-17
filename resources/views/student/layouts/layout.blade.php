<!DOCTYPE html>
<html lang="en">

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Student Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <!-- App favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('web/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('web/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('web/favicon-16x16.png') }}">
    <!-- Plugin css -->
    <link href="{{asset('student/assets/vendor/daterangepicker/daterangepicker.css' ) }}" rel="stylesheet" type="text/css">
    <link href="{{asset('student/assets/vendor/jsvectormap/jsvectormap.min.css' ) }}" rel="stylesheet" type="text/css">
    <!-- Theme Config Js -->
    <script src="{{asset('student/assets/js/hyper-config.js') }}"></script>
    <!-- Vendor css -->
    <link href="{{asset('student/assets/css/vendor.min.css' ) }}" rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="{{asset('student/assets/css/app-saas.min.css' ) }}" rel="stylesheet" type="text/css" id="app-style" />
    <!-- Icons css -->
    <link href="{{asset('student/assets/css/icons.min.css' ) }}" rel="stylesheet" type="text/css" />
    <!-- style css -->
    <link rel="stylesheet" href="{{asset('student/assets/css/style.css' ) }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body oncontextmenu="return false">
    <!-- Pre-loader -->
    <div id="preloader">
        <div id="status">
            <div class="bouncing-loader">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
    <!-- End Preloader-->

    <!-- Begin page -->
    <div class="wrapper">


        <!-- ========== Topbar Start ========== -->
        <div class="navbar-custom">
            <div class="topbar container-fluid">
                <div class="d-flex align-items-center gap-lg-2 gap-1">

                    <!-- Topbar Brand Logo -->
                    <div class="logo-topbar">
                        <!-- Logo light -->
                        <a href="{{ route('student.dashboard.home') }}" class="logo-light">
                            <span class="logo-lg">
                                <img src="{{asset('student/assets/images/ziel_sidebar.jpg' ) }}" alt="logo">
                            </span>
                            <span class="logo-sm">
                                <img src="{{asset('student/assets/images/ziel_sidebar.jpg' ) }}" alt="small logo">
                            </span>
                        </a>

                        <!-- Logo Dark -->
                        <a href="{{ route('student.dashboard.home') }}" class="logo-dark">
                            <span class="logo-lg">
                                <img src="{{asset('student/assets/images/ziel_sidebar.jpg' ) }}" alt="dark logo">
                            </span>
                            <span class="logo-sm">
                                <img src="{{asset('student/assets/images/ziel_sidebar.jpg' ) }}" alt="small logo">
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

                    <!-- Topbar Search Form -->
                    <div class="app-search dropdown d-none d-lg-block">

                        <div class="dropdown-menu dropdown-menu-animated dropdown-lg" id="search-dropdown">
                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h5 class="text-overflow mb-2">Found <span class="text-danger">17</span> results</h5>
                            </div>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="uil-notes font-16 me-1"></i>
                                <span>Analytics Report</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="uil-life-ring font-16 me-1"></i>
                                <span>How can I help you?</span>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="uil-cog font-16 me-1"></i>
                                <span>User profile settings</span>
                            </a>

                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow mb-2 text-uppercase">Users</h6>
                            </div>

                            <div class="notification-list">
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="d-flex">
                                        <img class="d-flex me-2 rounded-circle" src="{{asset('student/assets/images/users/avatar-2.jpg' ) }}"
                                            alt="Generic placeholder image" height="32">
                                        <div class="w-100">
                                            <h5 class="m-0 font-14">Erwin Brown</h5>
                                            <span class="font-12 mb-0">UI Designer</span>
                                        </div>
                                    </div>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="d-flex">
                                        <img class="d-flex me-2 rounded-circle" src="{{asset('student/assets/images/users/avatar-5.jpg' ) }}"
                                            alt="Generic placeholder image" height="32">
                                        <div class="w-100">
                                            <h5 class="m-0 font-14">Jacob Deo</h5>
                                            <span class="font-12 mb-0">Developer</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <ul class="topbar-menu d-flex align-items-center gap-3">
                    <li class="dropdown d-lg-none">
                        <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button"
                            aria-haspopup="false" aria-expanded="false">
                            <i class="ri-search-line font-22"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0">
                            <form class="p-3">
                                <input type="search" class="form-control" placeholder="Search ..."
                                    aria-label="Recipient's username">
                            </form>
                        </div>
                    </li>

                    <li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button"
                            aria-haspopup="false" aria-expanded="false">
                            <i class="ri-notification-3-line font-22"></i>
                            <span class="noti-icon-badge"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg py-0">
                            <div class="p-2 border-top-0 border-start-0 border-end-0 border-dashed border">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0 font-16 fw-semibold">Notification</h6>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="px-2" style="max-height: 300px;" data-simplebar>
                                @if($notifications->isEmpty())
                                    <div class="text-center py-4">
                                        <p class="text-muted">No new notifications.</p>
                                    </div>
                                @else
                                    @php
                                        $groupedNotifications = $notifications->groupBy(function($item) {
                                            return \Carbon\Carbon::parse($item->created_at)->diffInDays() == 0 ? 'Today' : (\Carbon\Carbon::parse($item->created_at)->diffInDays() == 1 ? 'Yesterday' : \Carbon\Carbon::parse($item->created_at)->format('d M Y'));
                                        });
                                    @endphp

                                    @foreach($groupedNotifications as $date => $group)
                                        <h5 class="text-muted font-13 fw-normal mt-2">{{ $date }}</h5>
                                        @foreach($group as $notification)
                                            <a href="{{ $notification->link ?? 'javascript:void(0);' }}" class="dropdown-item p-0 notify-item card read-noti shadow-none mb-2">
                                                <div class="card-body">
                                                    <span class="float-end noti-close-btn text-muted"><i class="mdi mdi-close"></i></span>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <div class="notify-icon bg-primary">
                                                                <i class="mdi mdi-comment-account-outline"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 text-truncate ms-2">
                                                            <h5 class="noti-item-title fw-semibold font-14">{{ $notification->title }} <small class="fw-normal text-muted ms-1">{{ $notification->created_at->diffForHumans() }}</small></h5>
                                                            <small class="noti-item-subtitle text-muted">{{ $notification->body }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    @endforeach
                                @endif
                            </div>
                            <a href="javascript:void(0);" class="dropdown-item text-center text-primary notify-item border-top py-2" data-bs-toggle="modal" data-bs-target="#allNotificationsModal">
    View All
</a>

                        </div>
                    </li>
                   


                    <!-- <li class="d-none d-sm-inline-block">
                        <a class="nav-link" data-bs-toggle="offcanvas" href="#theme-settings-offcanvas">
                            <i class="ri-settings-3-line font-22"></i>
                        </a>
                    </li> -->

                    <li class="d-none d-sm-inline-block">
                        <div class="nav-link" id="light-dark-mode" data-bs-toggle="tooltip" data-bs-placement="left"
                            title="Theme Mode">
                            <i class="ri-moon-line font-22"></i>
                        </div>
                    </li>


                    <li class="d-none d-md-inline-block">
                        <a class="nav-link" href="#" data-toggle="fullscreen">
                            <i class="ri-fullscreen-line font-22"></i>
                        </a>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle arrow-none nav-user px-2" data-bs-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="account-user-avatar">
                                <img src="{{ asset('storage/' . $profile_image) }}" alt="user-image" width="32"
                                    class="rounded-circle">
                            </span>
                            <span class="d-lg-flex flex-column gap-1 d-none">
                                <h5 class="my-0">{{ $studentName ?? 'Student' }}</h5>
                                <h6 class="my-0 fw-normal">Student</h6>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown px-2 py-2">
                             @forelse ($subscriptions as $subscription)
                                @php $course = $subscription->course; @endphp

                                <a href="{{ route('student.subjects.index', $course->id) }}" class="d-block mb-2" style="text-decoration: none;">
                                    <div class="position-relative rounded" 
                                        style="
                                        background-image: url('{{ $course->cover_image_web ? env('STORAGE_URL') . '/' . $course->cover_image_web : asset('student/assets/images/small/default-course.webp') }}');
                                        background-size: cover; 
                                        background-position: center; 
                                        height: 100px;
                                        ">
                                        <div class="position-absolute bottom-0 w-100 bg-dark bg-opacity-50 text-white text-flex-start py-1">
                                            <div class="ms-2">
                                                {{ $course->name }}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center text-muted px-2 py-2">
                                    No courses enrolled.
                                </div>
                            @endforelse
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- ========== Topbar End ========== -->

        <!-- ========== Left Sidebar Start ========== -->
        <div class="leftside-menu">

            <!-- Brand Logo Light -->
            <a href="{{ route('student.dashboard.home') }}" class="logo logo-light">
                <span class="logo-lg">
                    <img src="{{asset('student/assets/images/logo.png' ) }}" alt="logo">
                </span>
                <span class="logo-sm">
                    <img src="{{asset('student/assets/images/ziel_sidebar.jpg' ) }}" alt="small logo">
                </span>
            </a>

            <!-- Brand Logo Dark -->
            <a href="{{ route('student.dashboard.home') }}" class="logo logo-dark">
                <span class="logo-lg">
                    <img src="{{asset('student/assets/images/logo.png' ) }}" alt="dark logo">
                </span>
                <span class="logo-sm">
                    <img src="{{asset('student/assets/images/ziel_sidebar.jpg' ) }}" alt="small logo">
                </span>
            </a>

            <!-- Sidebar Hover Menu Toggle Button -->
            <div class="button-sm-hover" data-bs-toggle="tooltip" data-bs-placement="right" title="Show Full Sidebar">
                <i class="ri-checkbox-blank-circle-line align-middle"></i>
            </div>

            <!-- Full Sidebar Menu Close Button -->
            <div class="button-close-fullsidebar">
                <i class="ri-close-fill align-middle"></i>
            </div>

            <!-- Sidebar -->
            <div class="h-100" id="leftside-menu-container" data-simplebar>
                <!-- Leftbar User -->
                <div class="leftbar-user">
                    <a href="pages-profile.html">
                        <img src="{{asset('student/assets/images/users/avatar-1.jpg' ) }}" alt="user-image" height="42"
                            class="rounded-circle shadow-sm">
                        <span class="leftbar-user-name mt-2">Dominic Keller</span>
                    </a>
                </div>

                <!--- Sidemenu -->
                <ul class="side-nav">

                    <li class="side-nav-title">Navigation</li>

                    <!-- <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarDashboards" aria-expanded="false"
                            aria-controls="sidebarDashboards" class="side-nav-link">
                            <i class="ri-computer-fill"></i>
                            <span> Dashboards </span>
                        </a>
                        <div class="collapse" id="sidebarDashboards">
                            <ul class="side-nav-second-level">
                                <li>
                                    <a href="{{ route('student.portal.analytics') }}">Analytics</a>
                                </li>
                                <li>
                                    <a href="index.html">Ecommerce</a>
                                </li>
                            </ul>
                        </div>
                    </li> -->
                    <li class="side-nav-item">
                        <a href="{{ route('student.portal.analytics') }}" class="side-nav-link">
                            <i class="uil-home-alt"></i>
                            <span> Analytics </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="{{ route('student.dashboard.home') }}" class="side-nav-link">
                            <i class="uil-home-alt"></i>
                            <span> Home </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="{{ route('student.portal.courses') }}" class="side-nav-link">
                            <i class="ri-book-2-line"></i>
                            <span> Courses </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="{{ route('student.portal.subjects') }}" class="side-nav-link">
                            <i class="ri-book-fill"></i>
                            <span> Subjects </span>
                        </a>
                    </li>

                    <li class="side-nav-title">Learning & progress</li>

                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarEcommerce" aria-expanded="false"
                            aria-controls="sidebarEcommerce" class="side-nav-link">
                            <i class="uil-store"></i>
                            <span> Upcoming session </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarEcommerce">
                            <ul class="side-nav-second-level">
                                <li>
                                    <a href="{{ route('student.live.classes') }}">Live class</a>
                                </li>
                                <li>
                                    <a href="{{ route('student.exam.assessment') }}">Assessments</a>
                                </li>
                                <li>
                                    <a href="{{ route('student.portal.exam-mcq') }}">Exam</a>
                                </li>
                                <li>
                                    <a href="{{ route('student.portal.videos') }}">Videos</a>
                                </li>
                               <li class="side-nav-item">
                                    <a href="{{ route('student.portal.history.index') }}" class="side-nav-link">
                                        <span> Exam History </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="side-nav-item">
                        <a href="{{ route('student.certificates') }}" class="side-nav-link">
                            <i class="ri-file-list-2-fill"></i>
                            <span> My Certificate </span>
                        </a>
                    </li>

                    <li class="side-nav-title"> Account & Subscription</li>

                    <li class="side-nav-item">
                        <a href="{{ route('student.portal.subscription') }}" class="side-nav-link">
                            <i class="uil-briefcase"></i>
                            <span> Subscription </span>
                        </a>
                    </li>

                    <li class="side-nav-item">
                        <a href="{{ route('student.portal.referals') }}" class="side-nav-link">
                            <i class="uil-rss"></i>
                            <span> Refer </span>
                        </a>
                    </li>

                    <!-- <li class="side-nav-item">
                        <a href="{{ route('student.portal.referals') }}" class="side-nav-link">
                            <i class="uil-rss"></i>
                            <span> Earn </span>
                        </a>
                    </li> -->
                    

                    <li class="side-nav-item">
                        <a data-bs-toggle="collapse" href="#sidebarTasks" aria-expanded="false"
                            aria-controls="sidebarTasks" class="side-nav-link">
                            <i class="uil-clipboard-alt"></i>
                            <span> Terms & Conditions </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse" id="sidebarTasks">
                            <ul class="side-nav-second-level">
                                <li>
                                    <a href="#">Privacy policy</a>
                                </li>
                                <li>
                                    <a href="#">Terms and Conditions</a>
                                </li>
                                <li>
                                    <a href="#">Account Delety Policy</a>
                                </li>
                                <li>
                                    <a href="#">Cancelation and Refound</a>
                                </li>
                            </ul>
                        </div>  
                    </li>
                     <li class="side-nav-item">
                        <a href="{{ route('student.logout') }}" class="side-nav-link"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="ri-user-fill"></i>
                            <span>Logout</span>
                        </a>

                        <form id="logout-form" action="{{ route('student.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
                <!--- End Sidemenu -->

                <div class="clearfix"></div>
            </div>
        </div>
        <div class="content-page" style="background-color:rgba(195, 248, 245, 1);">


        @yield('student-analytics')
        @yield('student-index')
        @yield('student-dashboard')
        @yield('student-courses')
        @yield('student-subjects')
        @yield('student-mcq-assesment')
        @yield('student-referal')
        @yield('student-subscription')

       <!-- alert modal -->
        <div id="danger-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content modal-filled bg-danger">
                    <div class="modal-body p-4">
                        <div class="text-center">
                            <i class="ri-close-circle-line h1"></i>
                            <h4 class="mt-2">Validation Error!</h4>
                            
                            {{-- This div will be populated with errors from jQuery --}}
                            <div id="modal-error-list" class="mt-3"></div> 
                            
                            <button type="button" class="btn btn-light my-2" data-bs-dismiss="modal">Continue</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="success-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content modal-filled bg-success">
                    <div class="modal-body p-4">
                        <div class="text-center">
                            <i class="ri-check-line h1"></i>
                            <h4 class="mt-2">Success!</h4>
                            
                            <div id="modal-success-message" class="mt-3"></div> 
                            
                            <button type="button" class="btn btn-light my-2" data-bs-dismiss="modal">Continue</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <script>document.write(new Date().getFullYear())</script> © ZielTech Academy
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-end footer-links d-none d-md-block">
                                <a href="javascript: void(0);">About</a>
                                <a href="javascript: void(0);">Support</a>
                                <a href="javascript: void(0);">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->
        </div>

    </div>
    <!-- Notifications Modal -->
    <div class="modal fade" id="allNotificationsModal" tabindex="-1" aria-labelledby="allNotificationsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">All Notifications</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($notifications->isEmpty())
                        <div class="text-center py-4">
                            <p class="text-muted">No notifications available.</p>
                        </div>
                    @else
                        @php
                            $groupedNotifications = $notifications->groupBy(function($item) {
                                return \Carbon\Carbon::parse($item->created_at)->diffInDays() == 0 ? 'Today' : (\Carbon\Carbon::parse($item->created_at)->diffInDays() == 1 ? 'Yesterday' : \Carbon\Carbon::parse($item->created_at)->format('d M Y'));
                            });
                        @endphp

                        @foreach($groupedNotifications as $date => $group)
                            <h6 class="text-muted mt-3">{{ $date }}</h6>
                            @foreach($group as $notification)
                                <a href="{{ $notification->link ? url($notification->link) : 'javascript:void(0);' }}" class="text-decoration-none">
                                    <div class="card mb-2">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start">
                                                <div class="notify-icon bg-primary me-3">
                                                    <i class="mdi mdi-bell-ring-outline"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-semibold mb-1">{{ $notification->title }}</h6>
                                                    <p class="mb-1 text-muted">{{ $notification->body }}</p>
                                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @endforeach
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END wrapper -->
    @stack('scripts')
    <!-- Vendor js -->
    <script src="{{asset('student/assets/js/vendor.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Daterangepicker js -->
    <script src="{{asset('student/assets/vendor/daterangepicker/moment.min.js') }}"></script>
    <script src="{{asset('student/assets/vendor/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Vector Map js -->
    <script src="{{asset('student/assets/vendor/jsvectormap/jsvectormap.min.js') }}"></script>
    <script src="{{asset('student/assets/vendor/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{asset('student/assets/vendor/jsvectormap/maps/world.js') }}"></script>
    <!-- Analytics Dashboard App js -->
    <script src="{{asset('student/assets/js/pages/demo.dashboard-analytics.js') }}"></script>
    <script src="{{asset('student/assets/js/pages/demo.dashboard.js') }}"></script>
    <!-- App js -->
    <script src="{{asset('student/assets/js/app.min.js') }}"></script>

    <!-- attendance script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const attendanceDays = document.getElementById("attendance-days");
            const attendanceBar = document.getElementById("attendance-bar");
            const attendancePercentage = document.getElementById("attendance-percentage");
            const statusMessage = document.getElementById("status-message");

            let attendanceData = [1, 1, 1, 0, 1, -1, -1]; // 1 = Present, 0 = Absent, -1 = Unmarked

            function updateAttendance() {
                let presentCount = attendanceData.filter(day => day === 1).length;
                let totalMarked = attendanceData.filter(day => day !== -1).length;
                let percentage = totalMarked ? Math.round((presentCount / totalMarked) * 100) : 0;

                attendancePercentage.textContent = `${percentage}%`;

                if (percentage >= 75) {
                    statusMessage.innerHTML = "⭐ Great! You are going well at the moment";
                } else if (percentage >= 50) {
                    statusMessage.innerHTML = "⚠️ Keep improving your attendance!";
                } else {
                    statusMessage.innerHTML = "❌ Your attendance is low!";
                }

                // Update the attendance bar
                attendanceBar.innerHTML = "";
                attendanceData.forEach(status => {
                    let segment = document.createElement("div");
                    if (status === 1) segment.classList.add("present");
                    else if (status === 0) segment.classList.add("absent");
                    else segment.classList.add("unmarked");
                    attendanceBar.appendChild(segment);
                });
            }

            function renderAttendanceDays() {
                attendanceDays.innerHTML = "";
                attendanceData.forEach((status, index) => {
                    let badge = document.createElement("span");
                    badge.classList.add("badge");

                    if (status === 1) {
                        badge.classList.add("present-badge");
                        badge.textContent = index + 1;
                    } else if (status === 0) {
                        badge.classList.add("absent-badge");
                        badge.textContent = index + 1;
                    } else {
                        badge.classList.add("unmarked-badge");
                        badge.textContent = index + 1;
                    }

                    badge.addEventListener("click", () => {
                        if (attendanceData[index] === -1) {
                            attendanceData[index] = 1;
                        } else if (attendanceData[index] === 1) {
                            attendanceData[index] = 0;
                        } else {
                            attendanceData[index] = -1;
                        }
                        renderAttendanceDays();
                        updateAttendance();
                    });

                    attendanceDays.appendChild(badge);
                });
            }

            renderAttendanceDays();
            updateAttendance();
        });
        document.onkeydown = (e) => {
            if (e.key == 123) {
                e.preventDefault();
            }
            if (e.ctrlKey && e.shiftKey && e.key == 'I') {
                e.preventDefault();
            }
            if (e.ctrlKey && e.shiftKey && e.key == 'C') {
                e.preventDefault();
            }
            if (e.ctrlKey && e.shiftKey && e.key == 'J') {
                e.preventDefault();
            }
            if (e.ctrlKey && e.key == 'U') {
                e.preventDefault();
            }
        };
    </script>
</body>

</html>