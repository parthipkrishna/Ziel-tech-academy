<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Dashboard | Zieltech Acadamy Admin Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- App favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('web/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('web/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('web/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('web/site.webmanifest') }}">
        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <!-- jQuery (required for Select2) -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Select2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <!-- Daterangepicker css -->
        <link href="{{asset('dashboard/assets/vendor/daterangepicker/daterangepicker.css')}}" rel="stylesheet" type="text/css">
        <!-- Vector Map css -->
        <link href="{{asset('dashboard/assets/vendor/jsvectormap/css/jsvectormap.min.css')}}" rel="stylesheet" type="text/css">
        <!-- Theme Config Js -->
        <script src="{{asset('dashboard/assets/js/hyper-config.js')}}"></script>
        <!-- App css -->
        <link href="{{ asset('dashboard/assets/css/app-saas.min.css') }}" rel="stylesheet"  type="text/css" id="app-style" />
        <!-- Icons css -->
        <link href="{{asset('dashboard/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- Datatables css -->
        <link href="{{asset('dashboard/assets/vendor/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('dashboard/assets/vendor/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('dashboard/assets/vendor/datatables.net-fixedcolumns-bs5/css/fixedColumns.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('dashboard/assets/vendor/datatables.net-fixedheader-bs5/css/fixedHeader.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('dashboard/assets/vendor/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('dashboard/assets/vendor/datatables.net-select-bs5/css/select.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- file upload css -->
        <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/dropzone/dropzone-min.css') }}">
        <!-- Select2 css -->
        <link href="{{ asset('dashboard/assets/vendor/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- Quill css -->
        {{-- <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet" /> --}}
        <link rel="stylesheet" href="assets/vendor/quill/text-editor.css">
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
        <!--icon -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.js"></script>
    </head>
    <body>
        <!-- Begin page -->
        <div class="wrapper">
            <!-- ========== Topbar Start ========== -->
            <div class="navbar-custom">
                <div class="topbar container-fluid">
                    <div class="d-flex align-items-center gap-lg-2 gap-1">
                        <!-- Topbar Brand Logo -->
                        <div class="logo-topbar">
                            <!-- Logo light -->
                            <a href="" class="logo-light">
                                <span class="logo-lg">
                                    <img src="{{ asset('dashboard/logo/ziel_logo.png') }}" alt="logo" >
                                </span>
                                <span class="logo-sm">
                                    <img src="{{ asset('dashboard/logo/ziel_logo.png') }}" alt="small logo">
                                </span>
                            </a>
                            <!-- Logo Dark -->
                            <a href="" class="logo-dark">
                                <span class="logo-lg">
                                    <img src="{{ asset('dashboard/logo/ziel_logo.png') }}" alt="dark logo">
                                </span>
                                <span class="logo-sm">
                                    <img src="{{ asset('dashboard/logo/ziel_logo.png') }}" alt="small logo">
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
                                @foreach(auth()->user()->roles as $role)
                                    <h5 class="my-0">{{ $role->role_name }}</h5>
                                @endforeach
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
                                <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
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
            <!-- ========== Topbar End ========== -->

            <!-- ========== Left Sidebar Start ========== style="height: 90px; width: 50%; margin-top: 10px;"-->
            <div class="leftside-menu">
                <!-- Brand Logo Light -->
                <div class="logo logo-light">
                    <span class="logo-lg">
                        <img src="{{ asset('dashboard/logo/ziel_logo.png') }}" alt="logo" >
                        {{-- <h4 class="page-title">ZIEL-TECH ACADEMY</h4> --}}
                    </span>
                    <span class="logo-sm">
                        <img src="{{ asset('dashboard/logo/ziel_logo.png') }}" alt="small logo">
                    </span>
                </div>
                <!-- Brand Logo Dark -->
                <a href="{{ route('admin.analytics') }}" class="logo logo-dark">
                    <span class="logo-lg">
                        <img src="{{ asset('dashboard/logo/ziel_logo.png') }}" alt="dark logo">
                    </span>
                    <span class="logo-sm">
                        <img src="{{ asset('dashboard/logo/ziel_logo.png') }}" alt="small logo">
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
                        <a href="{{ route('admin.analytics') }}">
                            <img src="assets/images/users/avatar-1.jpg" alt="user-image" height="42"
                                class="rounded-circle shadow-sm">
                            <span class="leftbar-user-name mt-2"></span>
                        </a>
                    </div>

                    <!--- Sidemenu -->
                    <ul class="side-nav">

                        <!-- <li class="side-nav-item">
                            <a href="{{ route('admin.analytics') }}" class="side-nav-link">
                                <i class="uil-home-alt"></i>
                                <span> Dashboard </span>
                            </a>
                        </li> -->
                        @if(auth()->user()->hasPermission('user-management', 'view'))
                            <li class="side-nav-title">User Management</li>
                            <li class="side-nav-item">
                                <a data-bs-toggle="collapse" href="#sidebarRoles" aria-expanded="false" aria-controls="sidebarRoles" class="side-nav-link">
                                <i class="fa-solid fa-users"></i>
                                    <span>Users & Roles </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarRoles">
                                    <ul class="side-nav-second-level">
                                    @if(auth()->user()->hasPermission('users', 'view'))
                                        <li>
                                            <a href="{{ route('admin.users.index') }}">Users</a>
                                        </li>
                                    @endif
                                    @if(auth()->user()->hasPermission('roles-permissions', 'view'))
                                        <li>
                                            <a href="{{ route('admin.roles.index') }}">Roles & Permissions</a>
                                        </li>
                                    @endif
                                        {{-- <li> --}}
                                            {{-- <a href="#">User Roles</a> --}}
                                        {{-- </li> --}}
                                        {{-- <li>
                                            <a href="{{ route('admin.permissions.index') }}">Permissions</a>
                                        </li> --}}
                                        {{-- <li> 
                                            <a href="#">User Permissions</a>
                                        </li> --}}
                                    </ul>
                                </div>
                            </li>
                        @endif
                        @if(auth()->user()->hasPermission('academy-management', 'view'))
                            <li class="side-nav-title">Academy Management</li>
                            <li class="side-nav-item">
                                <a data-bs-toggle="collapse" href="#sidebarAcademy" aria-expanded="false" aria-controls="sidebarAcademy" class="side-nav-link">
                                <i class="fa-solid fa-graduation-cap"></i>
                                    <span> Academy </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarAcademy">
                                    <ul class="side-nav-second-level">
                                        @if(auth()->user()->hasPermission('company-info', 'view'))
                                            <li>
                                                <a href="{{ route('admin.company.infos.index') }}">Company Info</a>
                                            </li>
                                        @endif
                                        @if(auth()->user()->hasPermission('web-banners', 'view'))
                                            <li>
                                                <a href="{{ route('admin.web.banners.index') }}">Web Banners</a>
                                            </li>
                                        @endif
                                        @if(auth()->user()->hasPermission('quick-links', 'view'))
                                            <li>
                                                <a href="{{ route('admin.quicklinks.index') }}">Quick Links</a>
                                            </li>
                                        @endif
                                        <!-- <li>
                                            <a href="{{ route('admin.footer.index') }}">Footer Sections</a>
                                        </li> -->
                                    </ul>
                                </div>
                            </li>
                        @endif
                        @if(auth()->user()->hasPermission('course-management', 'view'))
                            <li class="side-nav-title">Course Management</li>
                            <li class="side-nav-item">
                                <a data-bs-toggle="collapse" href="#sidebarCourse" aria-expanded="false" aria-controls="sidebarCourse" class="side-nav-link">
                                    <i class="fa-solid fa-book"></i>
                                    <span> Courses & Subjects </span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarCourse">
                                    <ul class="side-nav-second-level">
                                        <li class="side-nav-item">
                                            @if(auth()->user()->hasPermission('online-courses', 'view'))
                                                <a data-bs-toggle="collapse" href="#sidebarOnlineourseAuth" aria-expanded="false" aria-controls="sidebarOnlineourseAuth">
                                                    <span> Online Courses </span>
                                                    <span class="menu-arrow"></span>
                                                </a>
                                            @endif
                                            <div class="collapse" id="sidebarOnlineourseAuth">
                                                <ul class="side-nav-third-level">
                                                    @if(auth()->user()->hasPermission('online-courses', 'view'))
                                                        <li>
                                                            <a href="{{ route('admin.courses.index') }}">Courses</a>
                                                        </li>
                                                    @endif
                                                    @if(auth()->user()->hasPermission('online-subjects', 'view'))
                                                        <li>
                                                            <a href="{{ route('admin.subjects.index') }}">Subjects</a>
                                                        </li>
                                                    @endif
                                                    @if(auth()->user()->hasPermission('online-students', 'view'))
                                                        <li>
                                                            <a href="{{ route('admin.student.enroll.index') }}">Enrollment</a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </li>

                                        <li class="side-nav-item">
                                            @if(auth()->user()->hasPermission('offline-courses', 'view'))
                                                <a data-bs-toggle="collapse" href="#sidebarOfflineourseAuth" aria-expanded="false" aria-controls="sidebarOfflineourseAuth">
                                                    <span> Offline Courses </span>
                                                    <span class="menu-arrow"></span>
                                                </a>
                                            @endif
                                            <div class="collapse" id="sidebarOfflineourseAuth">
                                                <ul class="side-nav-third-level">
                                                    @if(auth()->user()->hasPermission('offline-courses', 'view'))
                                                        <li>
                                                            <a href="{{ route('admin.offline.courses.index') }}">Courses</a>
                                                        </li>
                                                    @endif
                                                    @if(auth()->user()->hasPermission('offline-subjects', 'view'))
                                                        <li>
                                                            <a href="{{ route('admin.offline.subjects.index') }}">Subjects</a>
                                                        </li>
                                                    @endif
                                                    @if(auth()->user()->hasPermission('offline-students', 'view'))
                                                        <li>
                                                            <a href="{{ route('admin.offline.student.enroll.index') }}">Enrollment</a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endif

                        <!-- <li class="side-nav-title">Student Management</li> -->
                        <!-- <li class="side-nav-item">
                            @if(auth()->user()->hasPermission('student-achievements', 'view'))
                                <a data-bs-toggle="collapse" href="#sidebarStudent" aria-expanded="false" aria-controls="sidebarStudent" class="side-nav-link">
                                    <i class="fa-duotone fa-solid fa-graduation-cap"></i>
                                    <span>Student Achievements</span>
                                    <span class="menu-arrow"></span>
                                </a>
                            @endif
                            <div class="collapse" id="sidebarStudent">
                                <ul class="side-nav-second-level">
                                    @if(auth()->user()->hasPermission('student-testimonials', 'view'))
                                        <li>
                                            <a href="{{ route('admin.testimonials.index') }}">Student Testimonials</a>
                                        </li>
                                    @endif
                                    @if(auth()->user()->hasPermission('placements', 'view'))
                                        <li>
                                            <a href="{{ route('admin.placement.index') }}">Placements</a>
                                        </li>
                                    @endif
                                    <li>
                                        <a href="#">Placement Success Stories</a>
                                    </li>
                                </ul>
                            </div>
                        </li> -->
                        <!-- @if(auth()->user()->hasPermission('event-management', 'view'))
                        <li class="side-nav-title">Event Management</li>
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarEvent" aria-expanded="false" aria-controls="sidebarEvent" class="side-nav-link">
                                <i class="fa-solid fa-camera"></i>
                                <span>  Events & Media </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarEvent">
                                <ul class="side-nav-second-level">
                                    <li>
                                        <a href="{{ route('admin.events.index') }}">Events</a>
                                    </li>
                                    {{-- <li>
                                        <a href="#">Event Media</a>
                                    </li> --}}
                                </ul>
                            </div>
                        </li>
                        @endif -->
                        @if(auth()->user()->hasPermission('campus-management', 'view'))
                        <li class="side-nav-title">Campus Management</li>
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarContact" aria-expanded="false" aria-controls="sidebarContact" class="side-nav-link">
                            <i class="fa-solid fa-building-columns"></i>
                                <span> Campus info </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarContact">
                                <ul class="side-nav-second-level">
                                    <!-- <li>
                                        <a href="{{ route('admin.campuses.index') }}">Campuses</a>
                                    </li> -->
                                    @if(auth()->user()->hasPermission('campus-facilities', 'view'))
                                        <li>
                                            <a href="{{ route('admin.campusfacilities.index') }}">Campus Facilities</a>
                                        </li>
                                    @endif
                                    @if(auth()->user()->hasPermission('branches', 'view'))
                                    <li>
                                        <a href="{{ route('admin.branches.index') }}">Branches</a>
                                    </li>
                                    @endif
                                    @if(auth()->user()->hasPermission('contact-info', 'view'))
                                        <li>
                                            <a href="{{ route('admin.contacts.index') }}">Contact Info</a>
                                        </li>
                                    @endif
                                    @if(auth()->user()->hasPermission('social-media-link', 'view'))
                                        <li>
                                            <a href="{{ route('admin.socialmedia.index') }}">Social Media Links</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li> 
                        @endif                      
                    </ul>
                    <!--- End Sidemenu -->
                    <div class="clearfix"></div>
                </div>
            </div>
            <!-- ========== Left Sidebar End ========== -->
            <!-- ============================================================== -->
            <!-- Start Page Content Here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <div class="content">
                    <!-- Start Content-->
                    <div class="container-fluid">
                        @yield('home')
                        @yield('admin-home')
                        @yield('admin-profile')
                        @yield('analytics')
                        @yield('add-user')
                        @yield('list-user')
                        @yield('list-campus')
                        @yield('add-campus')
                        @yield('list-branches')
                        @yield('add-branch')
                        @yield('list-contact-info')
                        @yield('add-contact-info')
                        @yield('list-socialmedia')
                        @yield('add-socialmedia')
                        @yield('roles')
                        @yield('list-courses')
                        @yield('add-courses')
                        @yield('list-subjects')
                        @yield('add-subjects')
                        @yield('list-offline-courses')
                        @yield('add-offline-courses')
                        @yield('list-company-info')
                        @yield('add-company-info')
                        @yield('list-quicklinks')
                        @yield('add-quicklinks')
                        @yield('list-web-banner')
                        @yield('add-web-banner')
                        @yield('list-web-footer')
                        @yield('add-web-footer')
                        @yield('list-student-enrollment')
                        @yield('add-student-enrollment')
                        @yield('list-offline-student-enrollment')
                        @yield('add-offline-student-enrollment')
                        @yield('list-offline-subjects')
                        @yield('add-offline-subjects')
                        @yield('list-placement')
                        @yield('add-placement')
                        @yield('list-event')
                        @yield('add-event')
                        @yield('view-event')
                        @yield('testimonials')
                        @yield('add-testimonial')
                        @yield('permissions')
                        @yield('permission-index')
                        @yield('roles-add')
                    </div>
                    <!-- container -->
                </div>
                <!-- content -->

                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <script>document.write(new Date().getFullYear())</script> © INNERIX TECHNOLOGIES LLP
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- end Footer -->
            </div>
            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->

        </div>
        <!-- END wrapper -->

        <!-- Vendor js -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script src="{{asset('dashboard/assets/js/vendor.min.js')}}"></script>

        <!-- Daterangepicker js -->
        <script src="{{asset('dashboard/assets/vendor/daterangepicker/moment.min.js')}}"></script>
        <script src="{{asset('dashboard/assets/vendor/daterangepicker/daterangepicker.js')}}"></script>

        <!-- Apex Charts js -->
        <script src="{{asset('dashboard/assets/vendor/apexcharts/apexcharts.min.js')}}"></script>

        <!-- Vector Map Js -->
        <script src="{{asset('dashboard/assets/vendor/jsvectormap/js/jsvectormap.min.js')}}"></script>
        <script src="{{asset('dashboard/assets/vendor/jsvectormap/maps/world-merc.js')}}"></script>
        <script src="{{asset('dashboard/assets/vendor/jsvectormap/maps/world.js')}}"></script>

        <!-- Dashboard App js -->
        <script src="{{asset('dashboard/assets/js/pages/demo.dashboard.js')}}"></script>

        <!-- App js -->
        <script src="{{asset('dashboard/assets/js/app.min.js')}}"></script>

        <!-- Datatables js -->
        <script src="{{asset('dashboard/assets/vendor/datatables.net/js/dataTables.min.js')}}"></script>

        <script src="{{ asset('dashboard/assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('dashboard/assets/vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('dashboard/assets/vendor/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('dashboard/assets/vendor/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('dashboard/assets/vendor/datatables.net-fixedcolumns-bs5/js/fixedColumns.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('dashboard/assets/vendor/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
        <script src="{{ asset('dashboard/assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('dashboard/assets/vendor/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('dashboard/assets/vendor/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('dashboard/assets/vendor/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
        <script src="{{ asset('dashboard/assets/vendor/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('dashboard/assets/vendor/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
        <script src="{{ asset('dashboard/assets/vendor/datatables.net-select/js/dataTables.select.min.js') }}"></script>

        <!-- Datatable Demo Aapp js -->
        <script src="{{ asset('dashboard/assets/js/pages/demo.datatable-init.js') }}"></script>

        <!-- Datatable js -->
        <script src="{{ asset('dashboard/assets/vendor/jquery-datatables-checkboxes/js/dataTables.checkboxes.min.js') }}"></script>
        
        <!-- Product Demo App js -->
        {{-- <script src="{{ asset('dashboard/assets/js/pages/demo.products.js') }}"></script> --}}

        <!-- customer Demo App js -->
        <script src="{{ asset('dashboard/assets/js/pages/demo.customers.js') }}"></script>

        <!-- Code Highlight js -->
        <script src="{{ asset('dashboard/assets/vendor/highlightjs/highlight.pack.min.js') }}"></script>
        <script src="{{ asset('dashboard/assets/vendor/clipboard/clipboard.min.js') }}"></script>
        <script src="{{ asset('dashboard/assets/js/hyper-syntax.js') }}"></script>

        <!-- Dropzone File Upload js -->
        <script src="{{ asset('dashboard/assets/vendor/dropzone/dropzone-min.js') }}"></script>

        <!-- File Upload Demo js -->
        <script src="{{ asset('dashboard/assets/js/ui/component.fileupload.js') }}"></script>

        <!-- plugin js -->
        <script src="{{ asset('dashboard/assets/vendor/dropzone/min/dropzone.min.js') }}"></script>

        <!--  Select2 Js -->
        <script src="{{ asset('dashboard/assets/vendor/select2/js/select2.min.js') }}"></script>

        <!-- Initialize Quill editor -->
        {{-- <script src="{{asset('dashboard/assets/vendor/quill/text-editor.js') }}"></script> --}}

        <!-- Include the Quill library -->
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

        <!-- Chart.js-->
        {{-- <script src="{{'assets/vendor/chart.js/chart.min.js'}}"></script> --}}
        <!-- Sparkline Chart js -->
        <script src="{{ asset('dashboard/assets/vendor/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
        <!-- Sparkline Chart Demo js -->
        <script src="{{ asset('dashboard/assets/js/pages/demo.sparkline.js') }}"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
        
        <script src="{{ asset('dashboard/assets/js/pages/validation.js') }}"></script>

        <script>
            $(document).ready(function() {
                $('.datatable').DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "info": true
                });
            });
        </script>

        <script>
            $(document).ready(function () {
                var validator = $("#campusForm").validate({
                    rules: {
                        name: {
                            required: true,
                            minlength: 3
                        },
                        contact_phone: {
                            digits: true,
                            minlength: 10,
                            maxlength: 15
                        },
                        contact_email: {
                            email: true
                        },
                        address: {
                            required: true,
                            minlength: 5
                        }
                    },
                    messages: {
                        name: {
                            required: "Name is required",
                            minlength: "Name must be at least 3 characters long"
                        },
                        contact_phone: {
                            digits: "Only numbers are allowed",
                            minlength: "Phone number must be at least 10 digits",
                            maxlength: "Phone number cannot exceed 15 digits"
                        },
                        contact_email: {
                            email: "Enter a valid email address"
                        },
                        address: {
                            required: "Address is required",
                            minlength: "Address must be at least 5 characters long"
                        }
                    },
                    errorPlacement: function (error, element) {
                        error.addClass("text-danger").insertAfter(element);
                    },
                    highlight: function (element) {
                        $(element).addClass("is-invalid").removeClass("is-valid");
                    },
                    unhighlight: function (element) {
                        $(element).removeClass("is-invalid").addClass("is-valid");
                    },
                    onkeyup: function (element) {
                        $(element).valid();
                    },
                    onfocusout: function (element) {
                        $(element).valid();
                    }
                });     

                $("#campusForm button[type='submit']").click(function (event) {
                    if (!$("#campusForm").valid()) {
                        validator.focusInvalid();
                        event.preventDefault();
                    }
                });
            });
        </script>

        <script>
        $(document).ready(function () {
            var validator = $("#BranchForm").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },
                    address: {
                        required: true,
                        minlength: 5
                    },
                    campus_id: {
                        required: true // ✅ Make campus_id required
                    }
                },
                messages: {
                    name: {
                        required: "Name is required",
                        minlength: "Name must be at least 3 characters long"
                    },
                    address: {
                        required: "Address is required",
                        minlength: "Address must be at least 5 characters long"
                    },
                    campus_id: {
                        required: "Please select a campus" // ✅ Custom error message
                    }
                },
                errorPlacement: function (error, element) {
                    error.addClass("text-danger").insertAfter(element);
                },
                highlight: function (element) {
                    $(element).addClass("is-invalid").removeClass("is-valid");
                },
                unhighlight: function (element) {
                    $(element).removeClass("is-invalid").addClass("is-valid");
                }
            });     

            $("#BranchForm button[type='submit']").click(function (event) {
                if (!$("#BranchForm").valid()) {
                    validator.focusInvalid();
                    event.preventDefault();
                }
                $("#BranchForm input, #BranchForm select, #BranchForm textarea").each(function () {
                    var fieldName = $(this).attr("name");
                    if (fieldName !== "name" && fieldName !== "address" && fieldName !== "campus_id") {
                        $(this).removeClass("is-invalid").addClass("is-valid");
                    }
                });
            });
        });
        </script>
            
        <!-- Initialize Quill editor -->
        <script>
            var editor = new Quill('#editor', {
                theme: 'snow',
                placeholder: 'Write something...',
            });

            var editor2 = new Quill('#editor2', {
                theme: 'snow',
                placeholder: 'Write something...',
            });

        </script>

        <script>
            // Toggle file upload section based on media type
            document.getElementById('type').addEventListener('change', function() {
                const mediaType = this.value;

                if (mediaType === 'video') {
                    document.getElementById('image-upload-section').style.display = 'none';
                    document.getElementById('youtube-upload-section').style.display = 'none';
                    document.getElementById('video-upload-section').style.display = 'block';
                } else if (mediaType === 'image') {
                    document.getElementById('image-upload-section').style.display = 'block';
                    document.getElementById('video-upload-section').style.display = 'none';
                    document.getElementById('youtube-upload-section').style.display = 'none';
                } else if (mediaType === 'youtube') {
                    document.getElementById('youtube-upload-section').style.display = 'block';
                    document.getElementById('image-upload-section').style.display = 'none';
                    document.getElementById('video-upload-section').style.display = 'none';
                } else {
                    // In case no option is selected, hide both sections
                    document.getElementById('image-upload-section').style.display = 'none';
                    document.getElementById('video-upload-section').style.display = 'none';
                    document.getElementById('youtube-upload-section').style.display = 'none';
                }
            });

            // Initialize with default setting based on current selection (if any)
            document.addEventListener('DOMContentLoaded', function() {
                const mediaType = document.getElementById('type').value;

                if (mediaType === 'video') {
                    document.getElementById('video-upload-section').style.display = 'block';
                    document.getElementById('image-upload-section').style.display = 'none';
                    document.getElementById('youtube-upload-section').style.display = 'none';
                } else if (mediaType === 'image') {
                    document.getElementById('image-upload-section').style.display = 'block';
                    document.getElementById('video-upload-section').style.display = 'none';
                    document.getElementById('youtube-upload-section').style.display = 'none';
                } else if (mediaType === 'youtube') {
                    document.getElementById('image-upload-section').style.display = 'none';
                    document.getElementById('video-upload-section').style.display = 'none';
                    document.getElementById('youtube-upload-section').style.display = 'block';
                } else {
                    document.getElementById('image-upload-section').style.display = 'none';
                    document.getElementById('video-upload-section').style.display = 'none';
                }
            });
        </script>

        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    placeholder: "Choose ...",
                    allowClear: true
                });
            });
        </script>

        <script>

            $(document).ready(function() {
                $('.select2').select2();  // Initialize on page load
                // Reinitialize on modal open (if dropdown is inside a modal)
                $('#editCourseModal').on('shown.bs.modal', function() {
                    $('.select2').select2();
                });
                // Reinitialize if dropdown is loaded dynamically
                $(document).on('DOMNodeInserted', function() {
                    $('.select2').select2();
                });
            });
        </script>

        <script>
            $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Choose languages",
                allowClear: true
            });
        });     

        </script>
        <script>
                $(document).ready(function() {
            $('.select2-student').select2({
                placeholder: "Select Student",
                allowClear: true,
                language: {
                    noResults: function() {
                        return "No results found"; // Customize no results message
                    }
                }
            });
            });
        </script>
    </body>
</html>
