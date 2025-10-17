@extends('lms.layout.layout')
@section('list-notification')

    <!-- Pre-loader -->
    <div id="preloader">
        <div id="status">
            <div class="bouncing-loader">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>

    <!-- Page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <button type="button" class="btn btn-light mb-2 me-1">
                        <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-enquiry-modal">
                            <i class="mdi mdi-square-edit-outline"></i>
                            Import
                        </a>
                    </button>              
                    <a href="javascript:void(0);" id="exportBtn" class="btn btn-light mb-2 me-1">
                        <i class="mdi mdi-square-edit-outline"></i> Export
                    </a>
                </div>
                <h4 class="page-title">Toolkit Enquiries</h4>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-3">
            <input type="" id="date_range" class="form-control" placeholder="Select Date Range">
        </div>
        <div class="col-md-3">
            <select id="status_filter" class="form-select">
                <option value="" disabled selected>Statuses</option>
                <option value="request_placed">Request Placed</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
        <div class="col-md-3">
            <select id="batch_filter" class="form-select">
                <option value="" selected>All Batches</option>
                @foreach($batches as $batch)
                    <option value="{{ $batch->id }}">{{ $batch->name }} ({{ $batch->batch_number }})</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- DataTable -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="enquiries-datatable" class="table table-striped table-centered mb-0 w-100 dt-responsive nowrap">
                            <thead class="table-dark">
                                <tr>
                                    <th>Toolkit Name</th>
                                    <th>Student Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Full Address</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                        @foreach ($enquiries as $enquiry)
                        <!-- Update Price & Status Modal -->
                        <div class="modal fade" id="edit-tool-kit-modal{{ $enquiry->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="updateEnquiryLabel{{ $enquiry->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-md">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Update Price & Status</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('lms.toolkit.enquiry.updateStatus', $enquiry->id) }}" method="POST">
                                            @csrf

                                            <div class="mb-3">
                                                <label for="price{{ $enquiry->id }}" class="form-label">Total amount</label>
                                                <input type="number" step="0.01" name="total_amount" class="form-control"
                                                    id="price{{ $enquiry->id }}" value="{{ $enquiry->total_amount }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="status{{ $enquiry->id }}" class="form-label">Status</label>
                                                <select name="status" id="status{{ $enquiry->id }}" class="form-control">
                                                    <option value="request_placed" {{ $enquiry->status == 'request_placed' ? 'selected' : '' }}>
                                                        Request Placed
                                                    </option>
                                                    <option value="delivered" {{ $enquiry->status == 'delivered' ? 'selected' : '' }}>
                                                        Delivered
                                                    </option>
                                                    <option value="cancelled" {{ $enquiry->status == 'cancelled' ? 'selected' : '' }}>
                                                        Cancelled
                                                    </option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="modal fade" id="bs-enquiry-modal" tabindex="-1" role="dialog" aria-labelledby="importMarkListLabel" aria-hidden="true">
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
                                    <!-- Info message -->
                                    <div class="alert alert-info">
                                        <strong>Note:</strong> Only <b>Status</b>, <b>Address</b>, and <b>Total Amount</b> will be updated from the Excel file.
                                    </div>
                                    <form action="{{ route('lms.toolkit.enqiry.import.submit') }}" method="POST" enctype="multipart/form-data">
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
                    </div>
                </div>
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div> <!-- end row -->

    @push('scripts')
    <script>
        $(document).ready(function () {
        // Initialize daterangepicker
        $('#date_range').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('#date_range').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            $('#enquiries-datatable').DataTable().ajax.reload();
        });

        $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            $('#enquiries-datatable').DataTable().ajax.reload();
        });

        var table = $('#enquiries-datatable').DataTable({
            serverSide: true,
            ajax: {
                url: '{{ route("lms.toolkit.enqiry.data") }}',
                data: function(d) {
                    d.date_range = $('#date_range').val();
                    d.status = $('#status_filter').val();
                    d.batch_id = $('#batch_filter').val(); 
                }
            },
            pageLength: 25,
            columns: [
                { data: 'toolkit_name', name: 'toolkit_name' },
                { data: 'student_name', name: 'student_name' },
                { data: 'phone', name: 'phone' },
                { data: 'email', name: 'email' },
                { data: 'full_address', name: 'full_address', orderable: false, searchable: false },             
                { data: 'total_amount', name: 'total_amount' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at' },
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
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
            }
        });

        // Filters trigger
        $('#date_range, #status_filter, #batch_filter').on('change', function() {
            table.ajax.reload();
        });

        $('#search_box').on('keyup', function() {
            table.ajax.reload();
        });
    });

    $('#exportBtn').on('click', function() {
        let status = $('#status_filter').val() || '';
        let batch_id = $('#batch_filter').val() || '';
        let date_range = $('#date_range').val() || '';

        // redirect with filters as query params
        let url = '{{ route("lms.toolkit.enqiry.export") }}' 
                + '?status=' + status 
                + '&batch_id=' + batch_id 
                + '&date_range=' + date_range;

        window.location.href = url;
    });
</script>
@endpush

@endsection
