@extends('lms.layout.layout')
@section('list-students-feedback')
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
                        <li class="breadcrumb-item active">Student Feedbacks</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-5">
                        </div>
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-centered mb-0 w-100 dt-responsive nowrap" id="feedbacks-datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="display:none;">ID</th>
                                    <th>Student</th>
                                    <th>Name</th>
                                    <th>Admission Number</th>
                                    <th>Location</th>
                                    <th>Contact Number</th>
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
    $(document).ready(function () {
        $('#feedbacks-datatable').DataTable({
            serverSide: true,
            responsive: true,
            ajax: '{{ route('feedbacks.list.ajax') }}',
            pageLength: 25,
            columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'student', name: 'student', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'admission_number', name: 'admission_number' },
                { data: 'location', name: 'location' },
                { data: 'contact_number', name: 'contact_number' },
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
            }
        });
    });
    </script>
@endsection      