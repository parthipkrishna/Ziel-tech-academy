@extends('lms.layout.layout')
@section('content')

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
                        <li class="breadcrumb-item"><a href="javascript: void(0);">User</a></li> 
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </div>
                <h4 class="page-title">Users</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('users.create'))
                            <div class="col-sm-5">
                                <a href="{{ route('lms.add.user') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add</a>
                            </div>
                        @endif
                        <div class="col-sm-7">
                            <div class="text-sm-end">
                                <button type="button" class="btn btn-light mb-2 me-1" data-bs-toggle="modal" data-bs-target="#lmsImportModal">
                                    Import
                                </button>

                                <a href="{{ route('lms.export.users') }}" class="btn btn-light mb-2">Export</a>
                            </div>
                        </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-centered mb-0 w-100 dt-responsive nowrap" id="users-datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="display:none;">ID</th>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th style="width: 75px;">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <div class="modal fade" id="lmsImportModal" tabindex="-1" aria-labelledby="lmsImportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lmsImportModalLabel">Import Users</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <div class="mb-3">
                    <p>
                        Download the 
                        <a href="{{ asset('lms/excel/users.xlsx') }}" download>Sample Excel File</a>
                    </p>
                    <p class="text-muted">
                        Please ensure the headers and data format match the system requirements.
                    </p>
                </div>

                <form id="importForm" action="{{ route('lms.import.users') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Upload File</label>
                        <input type="file" name="import_file" class="form-control" accept=".xlsx,.csv" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Import</button>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
    #users-datatable tbody td {
        padding-top: 0px;
        padding-bottom: 0px;
        vertical-align: middle;
    }
</style>

    <!-- end row -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function () {
        $(document).on('change', '.status-toggle', function () {
            let courseId = $(this).data('id');
            let status = $(this).is(':checked') ? 1 : 0;
            $('#hidden_status_' + courseId).val(status);

            $.ajax({
                url: "{{ route('lms.update.user.status', ':id') }}".replace(':id', courseId),
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    status: status
                },
                success: function (response) {
                    console.log(response.message);
                },
                error: function (xhr) {
                    console.error("Status update failed", xhr);
                }
            });
        });
    });
</script>

    <script>
        function bindDeleteUserEvent() {
            $('.confirm-delete-user').off('click').on('click', function () {
                let userId = $(this).data('id');
                let url = '{{ route("lms.delete.user", ":id") }}'.replace(':id', userId);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function (response) {
                        $('#delete-alert-modal' + userId).modal('hide');

                        // Refresh the DataTable without reloading the whole page
                        $('#users-datatable').DataTable().ajax.reload(null, false);

                    },
                    error: function (xhr) {
                        alert('Something went wrong. Could not delete user.');
                    }
                });
            });
        }

    $(document).ready(function () {
        let userTable = $('#users-datatable').DataTable({
            serverSide: true,
            responsive: true,
            ajax: '{{ route('users.list.ajax') }}',
            pageLength: 25,
            columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'profile', name: 'profile', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'role', name: 'roles.role_name', orderable: false, searchable: true },
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

                // Re-bind delete button events on redraw
                bindDeleteUserEvent();
            }
        });
    });

     // Fetch modal HTML fresh via AJAX
    $(document).on('click', '.edit-user', function() {
        let userId = $(this).data('id');

        $.get('/lms/users/' + userId + '/edit', function(html) {
            $('#dynamic-modal-container').html(html);
            $('#bs-lmsEditUser-modal' + userId).modal('show');
        });
    });

      // Sync status change with modal's checkbox
    $(document).on('change', '.status-toggle', function() {
        let userId = $(this).data('id');
        let isChecked = $(this).is(':checked');
        $('#switch' + userId).prop('checked', isChecked);
    });
</script>

@endsection