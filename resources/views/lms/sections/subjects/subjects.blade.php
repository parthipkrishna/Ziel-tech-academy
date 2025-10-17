@extends('lms.layout.layout')
@section('list-subjects')
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
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Manage subjects</a></li>
                        <li class="breadcrumb-item active">subjects</li>
                    </ol>
                </div>
                <h4 class="page-title">subjects</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('subjects.create'))
                            <div class="col-sm-5">
                                <a href="{{ route('lms.add.subject') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                            </div>
                        @endif
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-centered mb-0 w-100 dt-responsive nowrap" id="subjects-datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="display:none;">ID</th>
                                    <th>Subject</th>
                                    <th>Name</th>
                                    <th>Course Name</th>
                                    <th>Total Hours</th>
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
    <!-- end row -->
    <script>
         $(document).on('change', '.status-toggle', function () {
        let subjectId = $(this).data('id');
        let status = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: '{{ route("lms.update.subject.status", ":id") }}'.replace(':id', subjectId),
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: status
            },
            success: function (response) {
                console.log(response.message);

                // Sync checkbox inside modal (if open)
                $('#status_' + subjectId).prop('checked', !!status);
            }
        });
    });

    function bindDeleteSubjectEvent() {
        $('.confirm-delete-subject').off('click').on('click', function () {
            let subjectId = $(this).data('id');
            let url = '{{ route("lms.delete.subject", ":id") }}'.replace(':id', subjectId);

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'POST'
                },
                success: function (response) {
                    $('#delete-alert-modal' + subjectId).modal('hide');

                    // Refresh DataTable or remove row manually
                    $('#subjects-datatable').DataTable().ajax.reload(null, false);
                },
                error: function (xhr) {
                    alert(xhr.responseJSON?.message || 'Something went wrong. Could not delete subject.');
                }
            });
        });
    }

    </script>
    <script>
    $(document).ready(function () {
        $('#subjects-datatable').DataTable({
            serverSide: true, // optional if you prefer client-side
            responsive: true,
            ajax: '{{ route('subjects.list.ajax') }}',
            pageLength: 25,
            columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'subject', name: 'subject', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'course_name', name: 'course_name' },
                { data: 'total_hours', name: 'total_hours' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
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
                bindDeleteSubjectEvent();
            },
        });
    });
    </script>

@endsection

        