<!DOCTYPE html>
<html lang="en">
@include('lms.inc.header')
<body>
    <!-- Begin page -->
    <div class="wrapper">

        
        <!-- ========== Topbar Start ========== -->
        @include('lms.components.navbar')
        <!-- ========== Topbar End ========== -->

        <!-- ========== Left Sidebar Start ========== -->
        @include('lms.components.sidebar')
        <!-- ========== Left Sidebar End ========== -->

        <!-- ============================================================== -->
        <!-- Start Page Content Here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">
                <!-- Start Content-->
                <div class="container-fluid">
                    @yield('content')
                    @yield('analytics')
                    @yield('list-students')
                    @yield('add-students')
                    @yield('list-student-enrollment')
                    @yield('add-student-enrollment')
                    @yield('list-courses')
                    @yield('add-courses')
                    @yield('list-course-section')
                    @yield('add-courses-section')
                    @yield('list-subjects')
                    @yield('add-subjects')
                    @yield('list-banners')
                    @yield('add-banners')
                    @yield('list-batches')
                    @yield('add-batches')
                    @yield('edit-batches')
                    @yield('list-notification')
                    @yield('add-notification')
                    @yield('list-faq')
                    @yield('add-faq')
                    @yield('list-influencer')
                    @yield('add-influencer')
                    @yield('list-students-feedback')
                    @yield('edit-student-feedback')
                    @yield('list-top-achievers')
                    @yield('add-top-achiever')
                    @yield('list-important_links')
                    @yield('add-important_links')
                    @yield('list-history')
                    @yield('list-payment')
                    @yield('add-payment')
                    @yield('list-liveclasses')
                    @yield('add-liveclasses')
                    <!-- content -->
                </div>
            </div>

            <!-- Footer Start -->
            @include('lms.inc.footer')
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- Theme Settings -->
    @include('lms.components.offcanvas')

    
@include('lms.inc.scripts')
<div id="danger-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content modal-filled" style="background-color: #993333;">
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
    <div class="modal-dialog modal-md">
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
</body>


<!-- Mirrored from coderthemes.com/hyper/saas/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 12 Mar 2025 05:34:11 GMT -->
</html>