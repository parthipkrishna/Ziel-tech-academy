@extends('lms.layout.layout')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Live class Report</li>
                </ol>
            </div>
            <h4 class="page-title">Live class Report</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Filter Section -->
                <form id="examReportFilter" class="row mb-3">
                    <div class="col-md-3">
                        <label for="batch_id" class="form-label">Batch</label>
                        <select name="batch_id" id="batch_id" class="form-control">
                            <option value="">All</option>
                             @foreach($batches as $batch)
                                <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="date_range" class="form-label">Date Range</label>
                        <input type="text" id="date_range" class="form-control" placeholder="Select Date Range">
                    </div>
                    <div class="col-md-3">
                        <label for="tutor_id" class="form-label">Tutor</label>
                        <select name="tutor_id" id="tutor_id" class="form-control">
                            <option value="">All</option>
                            @foreach($tutors as $tutor)
                                <option value="{{ $tutor->id }}">{{ $tutor->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end justify-content-end">
                        <a href="javascript:void(0);" id="exportLiveClassBtn" class="btn btn-light">
                            <i class="mdi mdi-download"></i> Export
                        </a>
                    </div>
                </form>
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-striped dt-responsive nowrap w-100" id="live-class-report-table">
                        <thead class="table-dark">
                            <tr>
                                <th>Live Class Name</th>
                                <th>Faculty</th>
                                <th>Batch</th>
                                <th>Students</th>
                                <th>Date</th>
                                <th>Time</th>
                                {{-- <th>Status</th> --}}
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    $(document).ready(function() {

        // Initialize date range picker
    $('#date_range').daterangepicker({
        autoUpdateInput: false,
        locale: { cancelLabel: 'Clear' }
    });

    $('#date_range').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        $('#live-class-report-table').DataTable().ajax.reload();
    });

    $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        $('#live-class-report-table').DataTable().ajax.reload();
    });


    // Export button
    $('#exportLiveClassBtn').on('click', function() {
    let batch_id = $('#batch_id').val() || '';
    let tutor_id = $('#tutor_id').val() || '';
    let date_range = $('#date_range').val() || '';

    let url = '{{ route("lms.live.class.report.export") }}'
            + '?batch_id=' + batch_id
            + '&tutor_id=' + tutor_id
            + '&date_range=' + date_range;

    window.location.href = url;
});
    let table = $('#live-class-report-table').DataTable({
        serverSide: true,
        ajax: {
            url: "{{ route('lms.live.class.report.ajax') }}",
            data: function(d) {
                d.batch_id = $('#batch_id').val();
                d.tutor_id = $('#tutor_id').val();
                d.date_range = $('#date_range').val();
            }
        },
        columns: [
            { data: 'name', name: 'live_classes.name' },
            { data: 'faculty_name', name: 'faculty_name' },
            { data: 'batch_name', name: 'batch_name' },
            { data: 'total_students', name: 'total_students' },
            { data: 'date_only', name: 'date_only' },
            { data: 'time_range', name: 'time_range' },
        ],
        pageLength: 25,
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

    $('#batch_id, #tutor_id, #date_range').on('change', function() {
        table.ajax.reload();
    });
});
</script>
@endsection
