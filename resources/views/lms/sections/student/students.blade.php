@extends('lms.layout.layout')
@section('list-students')
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
                        <li class="breadcrumb-item active">Students</li>
                    </ol>
                </div>
                <h4 class="page-title">Students</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('students.create'))
                            <div class="col-sm-5">
                                <a href="{{ route('lms.add.student') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                            </div>
                        @endif
                        <div class="col-sm-7">
                            <div class="text-sm-end">
                                <button type="button" class="btn btn-light mb-2 me-1">
                                    <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-importCertificate-modal">
                                        <i class="mdi mdi-square-edit-outline"></i>
                                        Import
                                    </a>
                                </button>              
                                <a href="{{ route('lms.export.student') }}" class="btn btn-light mb-2 me-1">
                                    <i class="mdi mdi-square-edit-outline"></i> Export
                                </a>
                            </div>
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-centered mb-0 w-100 dt-responsive nowrap" id="students-datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="display:none;">ID</th>
                                    <th>Student</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Admission Number</th>
                                    <th>Admission Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div class="modal fade" id="bs-importCertificate-modal" tabindex="-1" role="dialog" aria-labelledby="importMarkListLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="importMarkListLabel"><I>Import</I></h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                    </div>
                                    <div class="modal-body">
                                        @if (session('status'))
                                            <div class="alert alert-success">
                                                {{ session('status') }}
                                            </div>
                                        @endif                                

                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                {{ $errors->first() }}
                                            </div>
                                        @endif
                                        <div class="mb-3">
                                            <p>Download the <a href="{{ asset('lms/excel/student_demo_data_fixed.xlsx') }}" download>Sample Excel File</a> for Student Enroll</p>
                                        </div>

                                        <form action="{{ route('lms.import.student') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">Upload Excel File</label>
                                                <input type="file" name="file" class="form-control" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Import</button>
                                        </form>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <!-- end row -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
        $(document).on('change', '.status-toggle', function () {
            let courseId = $(this).data('id');
            let status = $(this).is(':checked') ? 1 : 0;
            $('#hidden_status_' + courseId).val(status);

            $.ajax({
                url: "{{ route('lms.update.student.status', ':id') }}".replace(':id', courseId),
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    status: status
                },
                success: function (response) {
                    console.log(response.message);
                    $('#status_' + courseId).prop('checked', !!status)
                },
                error: function (xhr) {
                    console.error("Status update failed", xhr);
                }
            });
        });
    });
        
    </script>
    <script>

        function bindDeleteStudentEvent() {
            $('.confirm-delete-student').off('click').on('click', function () {
                let studentId = $(this).data('id');
                let url = '{{ route("lms.delete.student", ":id") }}'.replace(':id', studentId);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'POST'
                    },
                    success: function (response) {
                        $('#delete-alert-modal' + studentId).modal('hide');

                        // Refresh the DataTable
                        $('#students-datatable').DataTable().row($(this).parents('tr')).remove().draw(false);
                    },
                    error: function () {
                        alert('Something went wrong. Could not delete student.');
                    }
                });
            });
        }

$(document).ready(function () {
    $('#students-datatable').DataTable({
        serverSide: true,
        responsive: true,
        ajax: '{{ route('students.list.ajax') }}',
        pageLength: 25,
            columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'profile', name: 'profile', orderable: false, searchable: false },
                { data: 'first_name', name: 'first_name' },
                { data: 'email', name: 'email' },
                { data: 'admission_number', name: 'admission_number' },
                { data: 'admission_date', name: 'admission_date' },
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
                bindDeleteStudentEvent(); 
            }
        });
    });
    </script>

@endsection      