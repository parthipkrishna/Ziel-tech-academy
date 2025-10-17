@extends('lms.layout.layout')
@section('list-courses')
     <div id="preloader">
        <div id="status">
            <div class="bouncing-loader">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Manage Courses</a></li>
                        <li class="breadcrumb-item active">Courses</li>
                    </ol>
                </div>
                <h4 class="page-title">Courses</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('courses.create'))
                            <div class="col-sm-5">
                                <a href="{{ route('lms.add.course') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                            </div>
                        @endif
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-centered mb-0 w-100 dt-responsive nowrap" id="courses-datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="display:none;">ID</th>
                                    <th>Cover</th>
                                    <th>Name</th>
                                    <th>Short Description</th>
                                    <th>Fee</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <script>
       // Handle status change in DataTable and sync with modal
        $(document).on('change', '.status-toggle', function () {
            let courseId = $(this).data('id');
            let isChecked = $(this).is(':checked') ? 1 : 0;

            // Update DB via AJAX
            $.ajax({
                url: '{{ route("lms.update.course.status", ":id") }}'.replace(':id', courseId),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: isChecked
                },
                success: function (response) {
                    console.log(response.message);

                    // Also update the status checkbox in the edit modal
                    $('#status-toggle-' + courseId).prop('checked', !!isChecked);
                    $('#hidden_status_' + courseId).val(isChecked);
                },
                error: function () {
                    alert('Failed to update course status');
                }
            });
        });

    function bindDeleteCourseEvent() {
    $('.confirm-delete-course').off('click').on('click', function () {
        let $btn = $(this);
        let courseId = $btn.data('id');
        let url = '{{ route("lms.delete.course", ":id") }}'.replace(':id', courseId);

        $.ajax({
    url: url,
    type: 'POST',
    data: {
        _token: '{{ csrf_token() }}',
        _method: 'POST'
    },
    success: function (response) {
        $('#delete-alert-modal' + courseId).modal('hide');

        // Refresh DataTable or remove row
        $('#courses-datatable').DataTable().ajax.reload(null, false);
    },
    error: function () {
        alert('Something went wrong. Could not delete course.');
    }
});

    });
}

    </script>
    <script>
    $(document).ready(function () {
        $('#courses-datatable').DataTable({
            serverSide: true,
            responsive: true,
            ajax: '{{ route('courses.list.ajax') }}',
            pageLength: 25,
            columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'cover', name: 'cover', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'short_description', name: 'short_description' },
                { data: 'course_fee', name: 'course_fee' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'></i>",
                    next: "<i class='mdi mdi-chevron-right'></i>"
                },
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                lengthMenu: 'Display <select class="form-select form-select-sm ms-1 me-1">' +
                    '<option value="5">5</option>' +
                    '<option value="10">10</option>' +
                    '<option value="20">20</option>' +
                    '<option value="-1">All</option>' +
                    '</select> entries'
            },
            pageLength: 10,
            order: [[0, "desc"]],
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                bindDeleteCourseEvent();
            }
        });
    });
    </script>

    <!-- end row -->
@endsection
