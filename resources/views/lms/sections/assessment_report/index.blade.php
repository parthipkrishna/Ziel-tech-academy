@extends('lms.layout.layout')

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Assessment Report</li>
                </ol>
            </div>
            <h4 class="page-title">Assessment Report</h4>
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
                        <label for="subject_id" class="form-label">Subject</label>
                        <select name="subject_id" id="subject_id" class="form-control">
                            <option value="">All</option>
                             @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach 
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="date_range" class="form-label">Date Range</label>
                        <input type="text" id="date_range" class="form-control" placeholder="Select Date Range">
                    </div>
                    <div class="col-md-3 d-flex align-items-end justify-content-end">
                        <a href="javascript:void(0);" id="exportBtn" class="btn btn-light">
                            <i class="mdi mdi-download"></i> Export
                        </a>
                    </div>
                </form>
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-striped dt-responsive nowrap w-100" id="exam-report-table">
                        <thead class="table-dark">
                            <tr>
                            <th>Exam Name</th>
                            <th>Subject</th>
                            <th>Session</th>
                            <th>Total Passed</th>
                            <th>Total Failed</th>
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
    $(document).ready(function () {
        $('#date_range').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });

    $('#date_range').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        $('#exam-report-table').DataTable().ajax.reload();
    });

    $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        $('#exam-report-table').DataTable().ajax.reload();
    });

    $('#exportBtn').on('click', function() {
        let batch_id = $('#batch_id').val() || '';
        let subject_id = $('#subject_id').val() || '';
        let date_range = $('#date_range').val() || '';

        let url = '{{ route("lms.assessment.report.export") }}'
                + '?batch_id=' + batch_id
                + '&subject_id=' + subject_id
                + '&date_range=' + date_range;

        window.location.href = url;
    });

        let table = $('#exam-report-table').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('lms.assessment.report.ajax') }}",
                data: function (d) {
                    d.batch_id = $('#batch_id').val();
                    d.subject_id = $('#subject_id').val();
                     d.date_range = $('#date_range').val();
                }
            },
            pageLength: 25,
            columns: [
                { data: 'exam_name', name: 'exam_name' },
                { data: 'subject_name', name: 'subject_name' },
                { data: 'session_name', name: 'session_name' },
                { data: 'total_passed', name: 'total_passed' },
                { data: 'total_failed', name: 'total_failed' },
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

      $('#batch_id, #subject_id').on('change', function() {
        table.ajax.reload();
    });
    });
</script>
@endsection
