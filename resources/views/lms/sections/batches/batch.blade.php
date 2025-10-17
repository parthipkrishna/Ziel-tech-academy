@extends('lms.layout.layout')
@section('list-batches')

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
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Manage Students</a></li>
                        <li class="breadcrumb-item active">Batches</li>
                    </ol>
                </div>
                <h4 class="page-title">Batches</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('batches.create'))
                            <div class="col-sm-5">
                                <a href="{{ route('lms.add.batch') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                            </div>
                        @endif
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-centered mb-0 w-100 dt-responsive nowrap" id="batches-datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="display:none;">ID</th>
                                    <th>Name</th>
                                    <th>Batch Number</th>
                                    <th>Student Limit</th>
                                    <th>Course</th>
                                    <th>Tutor</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <!-- Reusable Delete Modal -->
                        <div id="delete-confirm-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-body p-4 text-center">
                                        <i class="ri-information-line h1 text-info"></i>
                                        <h4 class="mt-2">Heads up!</h4>
                                        <p class="mt-3">Do you want to delete this Batch?</p>
                                        <button type="button" class="btn btn-danger my-2 confirm-delete-batch" data-id="">
                                            Delete
                                        </button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <script>
        $(document).on('change', '.status-toggle', function () {
        let batchId = $(this).data('id');
        let status = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: '{{ route("lms.update.batch.status", ":id") }}'.replace(':id', batchId),
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: status
            },
            success: function (response) {
                console.log(response.message);
            }
        });
    });

        $(document).on('click', '.btn-delete-batch', function () {
        const batchId = $(this).data('id');
        $('.confirm-delete-batch').data('id', batchId); // pass to modal button
        $('#delete-confirm-modal').modal('show');
    });

    $(document).on('click', '.confirm-delete-batch', function () {
        const batchId = $(this).data('id');
        const url = '{{ route("lms.delete.batch", ":id") }}'.replace(':id', batchId);

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'POST'
            },
            success: function (response) {
                $('#delete-confirm-modal').modal('hide');
                $('#batches-datatable').DataTable().ajax.reload(null, false);
                toastr.success(response.message || 'Batch deleted successfully.');
            },
            error: function (xhr) {
                toastr.error(xhr.responseJSON?.message || 'Failed to delete.');
            }
        });
    });

    </script>
    <script>
    $(document).ready(function () {
        $('#batches-datatable').DataTable({
            serverSide: true,
            responsive: true,
            ajax: '{{ route('batches.list.ajax') }}',
            pageLength: 25,
            columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'name', name: 'name' },
                { data: 'batch_number', name: 'batch_number' },
                { data: 'student_limit', name: 'student_limit' },
                { data: 'course_name', name: 'course_name' },
                { data: 'tutor', name: 'tutor', orderable: false, searchable: false },
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
            order: [[0, 'desc']],
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
            }
        });
    });
    </script>
    <!-- end row -->
@endsection
