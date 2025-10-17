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
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Student Payments</a></li>
                    <li class="breadcrumb-item active">Payments</li>
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
                        <label>Date Range</label>
                        <input type="text" id="date_range" class="form-control" autocomplete="off" placeholder="Select date range">
                    </div>
                    <div class="col-md-2">
                        <label>Month</label>
                        <select id="filter_month" class="form-control">
                            <option value="">All</option>
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Year</label>
                        <select id="filter_year" class="form-control">
                            <option value="">All</option>
                            @for ($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-centered mb-0 w-100 dt-responsive nowrap" id="payments-datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Amount</th>
                                    <th>Payment Gateway</th>
                                    <th>Transaction ID</th>
                                    <th>Payment Date</th>
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
        $('#date_range').daterangepicker({
            locale: { format: 'YYYY-MM-DD' },
            autoUpdateInput: false,
        });

        $('#date_range').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));
            table.draw();
        });

        $('#date_range').on('cancel.daterangepicker', function () {
            $(this).val('');
            table.draw();
        });

        $('#filter_month, #filter_year').on('change', function () {
            table.draw();
        });

        var table = $('#payments-datatable').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('payments.record.ajaxList') }}",
                data: function (d) {
                    let dateRange = $('#date_range').val().split(' to ');
                    d.start_date = dateRange[0] || null;
                    d.end_date = dateRange[1] || null;
                    d.month = $('#filter_month').val();
                    d.year = $('#filter_year').val();
                }
            },
            pageLength: 25,
            columns: [
                { data: 'student' },
                { data: 'course' },
                { data: 'amount' },
                { data: 'payment_gateway' },
                { data: 'transaction_id' },
                { data: 'paid_at' },
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
    });
</script>
@endsection