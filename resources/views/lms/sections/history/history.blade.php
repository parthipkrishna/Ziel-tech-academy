@extends('lms.layout.layout')
@section('list-history')

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
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Referral & Affiliate System</a></li>
                        <li class="breadcrumb-item active">History</li>
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
                        <table class="table table-striped table-centered mb-0 w-100 dt-responsive nowrap" id="referral-history-datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="display:none;">ID</th>
                                    <th>Count</th>
                                    <th>Referral Code</th>
                                    <th>Influencer</th>
                                    <th>Student</th>
                                    <th>Used</th>
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
        $(document).ready(function () {
            $('#referral-history-datatable').DataTable({
                serverSide: true, // Change to true if you expect large datasets
                responsive: true,
                ajax: '{{ route('referral.history.ajax') }}',
                pageLength: 25,
                columns: [
                    { data: 'id', visible: false },
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'referral_code', name: 'referralCode.code' },
                    { data: 'influencer', name: 'referralCode.influencer.name' },
                    { data: 'student', name: 'student.name' },
                    { data: 'used', name: 'used_at' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
                order: [[0, 'desc']],
                    responsive: true,
                    language: {
                        paginate: {
                            previous: "<i class='mdi mdi-chevron-left'></i>",
                            next: "<i class='mdi mdi-chevron-right'></i>"
                        }
                    },
                    drawCallback: function() {
                        $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                        
                        // Initialize status toggle switches
                    }
                });
        });
</script>

@endsection
