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
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Student Subscriptions</a></li>
                    <li class="breadcrumb-item active">Subscriptions</li>
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
                    <div class="col-sm-7">
                    </div><!-- end col-->
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Course</label>
                        <select id="filter-course" class="form-control">
                            <option value="">All</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Start Date</label>
                        <input type="date" id="filter-start-date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>End Date</label>
                        <input type="date" id="filter-end-date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Status</label>
                        <select id="filter-status" class="form-control">
                            <option value="">All</option>
                            <option value="active">Active</option>
                            <option value="expired">Expired</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-centered mb-0 w-100 dt-responsive nowrap" id="subscription-datatable">
                        <thead class="table-dark">
                            <tr>
                                <th style="display:none;">ID</th>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Batch</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                            </tr>
                        </thead> 
                    </table>
                </div> 
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col -->
</div>

    <script>
    $(document).ready(function () {
    var table = $('#subscription-datatable').DataTable({
        serverSide: true,
        ajax: {
            url: "{{ route('lms.subscriptions.ajaxList') }}",
            data: function (d) {
                d.course_id = $('#filter-course').val();
                d.start_date = $('#filter-start-date').val();
                d.end_date = $('#filter-end-date').val();
                d.status = $('#filter-status').val();
            }
        },
        pageLength: 25,
        columns: [
            { data: 'id', visible: false },
            { data: 'student', name: 'student.first_name' },
            { data: 'course', name: 'course.name' },
            { data: 'batch', name: 'batch' },
            { data: 'start_date' },
            { data: 'end_date' },
            { data: 'status', orderable: false, searchable: false },
        ],
        order: [[0, 'desc']],
        responsive: true,
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'></i>",
                next: "<i class='mdi mdi-chevron-right'></i>"
            }
        },
        drawCallback: function () {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
        }
    });

    $('#filter-course, #filter-start-date, #filter-end-date, #filter-status').change(function () {
        table.ajax.reload();
    });
});

    </script>
@endsection