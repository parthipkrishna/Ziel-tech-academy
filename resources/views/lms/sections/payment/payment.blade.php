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
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Referral & Affiliate System</a></li>
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
                        @if(auth()->user()->hasPermission('referral-payments.create'))
                            <div class="col-sm-5">
                                <a href="{{route('lms.add.payment')}}" class="btn btn-danger mb-2 action-icon"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                            </div>
                        @endif
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-centered mb-0 w-100 dt-responsive nowrap" id="payment-datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="display:none;">ID</th>
                                    <th>Influencer</th>
                                    <th>Withdrawal Amount</th>
                                    <th>Payment Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                        @foreach ($influencer_payments as $payment)
                        <!-- Edit Modal-->
                        <div class="modal fade" id="bs-editPayment-modal{{ $payment->id }}" tabindex="-1" role="dialog" aria-labelledby="editPaymentLabel{{ $payment->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="editPaymentLabel{{ $payment->id }}">Edit Payment</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('lms.update.payment', $payment->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    
                                                    <div class="mb-3">
                                                        <label for="district" class="form-label">Influencers</label>
                                                        <select class="form-select influencer-select" data-payment-id="{{ $payment->id }}" name="influencer_id">
                                                            @foreach ($influencers as $influencer)
                                                                <option value="{{ $influencer->id }}" {{ $influencer->id == $payment->influencer_id ? 'selected' : '' }}>
                                                                    {{ $influencer->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Methods</label>
                                                        <select name="method" class="form-control">
                                                            <option value="">Select option</option>
                                                            @foreach($payment_methods as $key => $label)
                                                                <option value="{{ $key }}" {{  $payment->method  == $key ? 'selected' : '' }}>{{ $label }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Status</label>
                                                        <select name="status" class="form-control payment-status" data-payment-id="{{ $payment->id }}">
                                                            <option value="">Select option</option>
                                                            @foreach($payment_status as $key => $label)
                                                                <option value="{{ $key }}" {{  $payment->status  == $key ? 'selected' : '' }}>{{ $label }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mb-3 transaction-id-group d-none" id="transaction_id_group_{{ $payment->id }}">
                                                        <input type="text" class="form-control" name="transaction_id" value="{{ $payment->transaction_id }}">
                                                    </div>
                                                        <div class="mb-3">
                                                        <label class="form-label">Transaction ID (if any)</label>
                                                        <input type="text" class="form-control" id="transaction_id_{{ $payment->id }}" value="{{  $payment->transaction_id }}" >
                                                    </div>
                                                    
                                                </div>  

                                                <div class="col-lg-6">
                                                    @php
                                                        $totalAmount = $payment->influencer->getTotalCommission();
                                                        $balanceAmount = $payment->influencer->getBalance();
                                                        $totalWithdrawal = $payment->influencer->getTotalWithdrawal();
                                                    @endphp

                                                    <!-- Hidden fields -->
                                                    <input type="hidden" id="total_withdrawal_{{ $payment->id }}" value="{{ $totalWithdrawal }}">
                                                    <input type="hidden" id="old_withdrawal_{{ $payment->id }}" value="{{ $payment->current_withdrawal }}">

                                                    <div class="mb-3">
                                                        <label class="form-label">Total Amount</label>
                                                        <input type="text" class="form-control" id="total_amount_{{ $payment->id }}" value="{{ number_format($totalAmount, 2) }}" disabled>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Balance Amount</label>
                                                        <input type="text" class="form-control" id="balance_amount_display_{{ $payment->id }}" value="{{ number_format($balanceAmount, 2) }}" disabled>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label for="current_withdrawal" class="form-label">Withdrawal Amount</label>
                                                        <input type="text" name="current_withdrawal" value="{{ $payment->current_withdrawal }}" class="form-control amount-input" id="current_withdrawal{{ $payment->id }}" data-id="{{ $payment->id }}">
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="example-textarea" class="form-label">Notes</label>
                                                        <textarea class="form-control" name="notes" value="" id="example-textarea" rows="2">{{ $payment->notes }}</textarea>
                                                    </div>
                                                </div>
                                            </div> 
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </form>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                        @endforeach      
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>

@push('scripts')
<script>
    function updateBalance(paymentId) {
        const totalAmount = parseFloat($('#total_amount_' + paymentId).val().replace(/,/g, '')) || 0;
        const totalWithdrawal = parseFloat($('#total_withdrawal_' + paymentId).val()) || 0;
        const oldWithdrawal = parseFloat($('#old_withdrawal_' + paymentId).val()) || 0;
        const newWithdrawal = parseFloat($('#current_withdrawal' + paymentId).val()) || 0;

        const updatedWithdrawal = totalWithdrawal - oldWithdrawal + newWithdrawal;
        const newBalance = (totalAmount - updatedWithdrawal).toFixed(2);

        $('#balance_amount_display_' + paymentId).val(newBalance);
    }

    // 🔁 Only trigger on user input
    $(document).on('input', '.amount-input', function () {
        const paymentId = $(this).data('id');
        updateBalance(paymentId);
    });

    // 🛑 Don't do anything on modal open
    $(document).on('shown.bs.modal', '.modal', function () {
        // intentionally left blank
    });

    // 🛑  when influencer is changed
   $(document).on('change', '.influencer-select', function () {
    const paymentId = $(this).data('payment-id');
    const influencerId = $(this).val();
    const url = "{{ route('lms.selected.influencer', ':id') }}".replace(':id', influencerId);
    $.ajax({
        url: url,
        method: 'GET',
        success: function(data) {
            // Safe parse
            const totalAmount = parseFloat(data.total_amount) || 0;
            const totalWithdrawal = parseFloat(data.total_withdrawal) || 0;
            const balance = parseFloat(data.balance) || 0;

            $('#total_amount_' + paymentId).val(totalAmount.toFixed(2));
            $('#total_withdrawal_' + paymentId).val(totalWithdrawal);
            $('#balance_amount_display_' + paymentId).val(balance.toFixed(2));

            // Reset current withdrawal and old withdrawal
            $('#current_withdrawal' + paymentId).val('');
            $('#old_withdrawal_' + paymentId).val(0);
        }

    });
});

</script>
@endpush
<script>
    $(document).ready(function () {
        $('#payment-datatable').DataTable({
            serverSide: true,
            ajax: "{{ route('payments.ajaxList') }}",
            pageLength: 25, 
            columns: [
                { data: 'id', visible: false },
                { data: 'influencer' },
                { data: 'current_withdrawal' },
                { data: 'payment_date' },
                { data: 'status', orderable: false, searchable: false },
                { data: 'action', orderable: false, searchable: false }
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
            }
        });
    });
</script>

@endsection