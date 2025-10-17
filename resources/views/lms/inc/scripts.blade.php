
        <!-- Vendor js -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script src="{{asset('lms/assets/js/vendor.min.js')}}"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

        <!-- Daterangepicker js -->
        <script src="{{asset('lms/assets/vendor/daterangepicker/moment.min.js')}}"></script>
        <script src="{{asset('lms/assets/vendor/daterangepicker/daterangepicker.js')}}"></script>

        <!-- Apex Charts js -->
        <script src="{{asset('lms/assets/vendor/apexcharts/apexcharts.min.js')}}"></script>

        <!-- Vector Map Js -->
        <script src="{{asset('lms/assets/vendor/jsvectormap/js/jsvectormap.min.js')}}"></script>
        <script src="{{asset('lms/assets/vendor/jsvectormap/maps/world-merc.js')}}"></script>
        <script src="{{asset('lms/assets/vendor/jsvectormap/maps/world.js')}}"></script>

        <!-- Dashboard App js -->
        <script src="{{asset('lms/assets/js/pages/demo.dashboard.js')}}"></script>

        <!-- App js -->
        <script src="{{asset('lms/assets/js/app.min.js')}}"></script>
        <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
        <!-- App js -->
        <script src="{{asset('lms/assets/js/scripts.js')}}"></script>

        <!-- Datatables js -->
        <script src="{{asset('lms/assets/vendor/datatables.net/js/dataTables.min.js')}}"></script>

        <script src="{{ asset('lms/assets/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('lms/assets/vendor/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('lms/assets/vendor/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('lms/assets/vendor/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('lms/assets/vendor/datatables.net-fixedcolumns-bs5/js/fixedColumns.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('lms/assets/vendor/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js') }}"></script>
        <script src="{{ asset('lms/assets/vendor/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('lms/assets/vendor/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('lms/assets/vendor/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('lms/assets/vendor/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
        <script src="{{ asset('lms/assets/vendor/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('lms/assets/vendor/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
        <script src="{{ asset('lms/assets/vendor/datatables.net-select/js/dataTables.select.min.js') }}"></script>

        <!-- Datatable Demo Aapp js -->
        <script src="{{ asset('lms/assets/js/pages/demo.datatable-init.js') }}"></script>

        <!-- Datatable js -->
        <script src="{{ asset('lms/assets/vendor/jquery-datatables-checkboxes/js/dataTables.checkboxes.min.js') }}"></script>
        
        <!-- Product Demo App js -->
        {{-- <script src="{{ asset('lms/assets/js/pages/demo.products.js') }}"></script> --}}

        <!-- customer Demo App js -->
        <script src="{{ asset('lms/assets/js/pages/demo.customers.js') }}"></script>

        <!-- Code Highlight js -->
        <script src="{{ asset('lms/assets/vendor/highlightjs/highlight.pack.min.js') }}"></script>
        <script src="{{ asset('lms/assets/vendor/clipboard/clipboard.min.js') }}"></script>
        <script src="{{ asset('lms/assets/js/hyper-syntax.js') }}"></script>

        <!-- Dropzone File Upload js -->
        <script src="{{ asset('lms/assets/vendor/dropzone/dropzone-min.js') }}"></script>

        <!-- File Upload Demo js -->
        <script src="{{ asset('lms/assets/js/ui/component.fileupload.js') }}"></script>

        <!-- plugin js -->
        <script src="{{ asset('lms/assets/vendor/dropzone/min/dropzone.min.js') }}"></script>

        <!--  Select2 Js -->
        <script src="{{ asset('lms/assets/vendor/select2/js/select2.min.js') }}"></script>

        <!-- Initialize Quill editor -->
        {{-- <script src="{{asset('lms/assets/vendor/quill/text-editor.js') }}"></script> --}}

        <!-- Include the Quill library -->
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

        <!-- Chart.js-->
        {{-- <script src="{{'assets/vendor/chart.js/chart.min.js'}}"></script> --}}
        <!-- Sparkline Chart js -->
        <script src="{{ asset('lms/assets/vendor/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
        <!-- Sparkline Chart Demo js -->
        <script src="{{ asset('lms/assets/js/pages/demo.sparkline.js') }}"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
        
        <script src="{{ asset('lms/assets/js/pages/validation.js') }}"></script>

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
                $('.select2').select2();  
                $('#editCourseModal').on('shown.bs.modal', function() {
                    $('.select2').select2();
                });
                $(document).on('DOMNodeInserted', function() {
                    $('.select2').select2();
                });
            });
        </script>
        <script>
        document.addEventListener("DOMContentLoaded", function () {
            let sidebarContainer = document.querySelector("#leftside-menu-container .simplebar-content-wrapper");
            let activeItem = document.querySelector(".side-nav-link.active");

            if (sidebarContainer && activeItem) {
                activeItem.scrollIntoView({
                    behavior: "smooth",
                    block: "center"
                });
            }
        });
    </script>

        @stack('scripts')
        @stack('feedback-scripts')
        @stack('notification-scripts')
        @stack('liveclass-scripts')

