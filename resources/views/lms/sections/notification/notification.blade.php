@extends('lms.layout.layout')
@section('list-notification')

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

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Marketing Management</a></li>
                        <li class="breadcrumb-item active">Notifications</li>
                    </ol>
                </div>
                <h4 class="page-title">Notifications</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('notifications.create'))
                            <div class="col-sm-5">
                                <a href="{{ route('lms.add.notification') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                            </div>
                        @endif
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-centered mb-0 w-100 dt-responsive nowrap" id="products-datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="display:none;">ID</th>
                                    <th>Title</th>
                                    <th>Body</th>
                                    <th>Category Type</th>
                                    <th>Delivered Count</th>
                                    <th>Create at</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <!-- end row -->

    @push('notification-scripts')
    <script>
    console.log("DataTables AJAX URL: ", "{{ route('lms.live.classes.data') }}");
</script>
        <script type="text/javascript">
            $(document).ready(function () {
                if ($.fn.DataTable.isDataTable('#products-datatable')) {
                    $('#products-datatable').DataTable().destroy();
                }

                $('#products-datatable').DataTable({
                    processing: false,
                    serverSide: true,
                    ajax: {
                        url: '{{ route("lms.notifications.data") }}',
                        beforeSend: function () {
                            $('#preloader').show(); // Show preloader before AJAX call
                        },
                        complete: function () {
                            $('#preloader').fadeOut(); // Hide after data loads
                        },
                        error: function (xhr, error, thrown) {
                            $('#preloader').fadeOut(); // Also hide on error
                            console.error('DataTable AJAX error:', error);
                        }
                    },
                    pageLength: 25, 
                    columns: [
                        { data: 'id', name: 'id', visible: false },
                        { data: 'title', name: 'title' },
                        { data: 'body', name: 'body' },
                        { data: 'category_type', name: 'category_type' },
                        { data: 'delivered_count', name: 'delivered_count', orderable: false, searchable: false },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'status', name: 'status', orderable: false, searchable: false }
                    ],
                    drawCallback: function () {
                        $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                        $("#products-datatable_length label").addClass("form-label");
                        document.querySelectorAll(".dataTables_wrapper .row .col-md-6").forEach(function (el) {
                            el.classList.add("col-sm-6");
                            el.classList.remove("col-sm-12", "col-md-6");
                        });
                    }
                });
            });
        </script>
    @endpush

@endsection

        