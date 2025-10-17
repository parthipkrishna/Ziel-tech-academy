@extends('lms.layout.layout')
@section('add-payment')


    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Payments</a></li>
                        <li class="breadcrumb-item active">Add Payment</li>
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
                    <h4 class="header-title mb-3">Add Payment</h4>    
                    <div class="row justify-content-center">
                        @if ($message = session()->get('message'))
                            <div class="alert alert-success text-center w-75">
                                <h6 class="text-center fw-bold">{{ $message }}...</h6>
                            </div>
                        @endif  
                    </div>
                                            
                    <div class="tab-content">
                        <div class="tab-pane show active" id="custom-styles-preview">
                            <form class="needs-validation" id="PaymentForm" method="POST" action="{{ route('lms.store.payment') }}" enctype="multipart/form-data"  novalidate>
                                @csrf
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="influencer" class="form-label">Influencer</label>
                                        <select class="form-select" id="example-select" name="influencer_id" required>
                                            <option value="">Select Influencer</option>
                                            @foreach ($influencers as $influencer)
                                                <option value="{{ $influencer->id }}">{{ $influencer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                       
                                        <div class="mb-3">
                                            <label for="total_amount" class="form-label">Current Balance Amount</label>
                                            <input type="text" name="total_amount"  value="{{ old('total_amount') }}" class="form-control"  id="total_amount"  placeholder="Enter Total Amount" disabled>
                                            @error('total_amount')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="current_withdrawal" class="form-label">Withdrawal Amount</label>
                                            <input type="text" name="current_withdrawal"  value="{{ old('current_withdrawal') }}" class="form-control"  id="current_withdrawal"  placeholder="Enter Withdrawal Amount" required>
                                            @error('current_withdrawal')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="payment_date" class="form-label">Payment Date</label>
                                            <input type="date" name="payment_date"  value="{{ old('payment_date') }}" class="form-control"  id="payment_date"  placeholder="Enter Payment Date" required>
                                            @error('payment_date')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Methods</label>
                                            <select name="method" class="form-control" required>
                                                <option value="">Select option</option>
                                                @foreach($payment_methods as $key => $label)
                                                    <option value="{{ $key }}" {{ old('method') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            @error('method')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                    </div>  

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="balance_amount" class="form-label">Balance Amount</label>
                                            <input type="text" id="balance_amount_display" class="form-control" placeholder="Enter Balance Amount" value="{{ old('balance_amount') }}" disabled>
                                            @error('balance_amount')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="gst_number" class="form-label">GST Number or PAN Number</label>
                                            <input type="text" 
                                                    name="gst_number"  
                                                    value="{{ old('gst_number') }}" 
                                                    class="form-control text-uppercase"  
                                                    id="gst_number"  
                                                    placeholder="Enter GST Number" 
                                                    style="text-transform: uppercase;" 
                                                    oninput="this.value = this.value.toUpperCase()">
                                            @error('gst_number')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3 ">
                                            <label for="attachment_path" class="form-label">Attachment</label>
                                            <input type="file" class="form-control" id="attachment_path" name="attachment_path">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="example-textarea" class="form-label">Notes</label>
                                            <textarea class="form-control" name="notes" value="{{ old('notes') }}" id="example-textarea" rows="2"></textarea>
                                            @error('notes')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                    </div>
                                </div> 

                                <!-- Submit Button -->
                                <div class="text-start">
                                    <button type="reset" class="btn btn-danger">Reset</button>
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </form>
                        </div> <!-- end preview-->
                    </div> <!-- end tab-content-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

@push('scripts')
<script>
$(document).ready(function () {
    $('#example-select').on('change', function () {
        const influencerId = $(this).val();

        if (influencerId) {
            const url = "{{ route('lms.total.payment', ':id') }}".replace(':id', influencerId);

            $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    const total = parseFloat(response.total_amount || 0).toFixed(2);
                    $('#total_amount').val(total);

                    // Clear balance when influencer changes
                    $('#balance_amount_display').val('');
                    $('#current_withdrawal').val('');
                },
                error: function () {
                    $('#total_amount').val('');
                    $('#balance_amount_display').val('');
                    $('#current_withdrawal').val('');
                }
            });
        } else {
            $('#total_amount').val('');
            $('#balance_amount_display').val('');
            $('#current_withdrawal').val('');
        }
    });

    $('#current_withdrawal').on('input', function () {
        const total = parseFloat($('#total_amount').val()) || 0;
        const withdrawn = parseFloat($(this).val()) || 0;

        if (!isNaN(withdrawn)) {
            const balance = total - withdrawn;
            $('#balance_amount_display').val(balance.toFixed(2));
        } else {
            $('#balance_amount_display').val('');
        }
    });
});

$(document).ready(function () {
                $('#PaymentForm').submit(function (e) {
                    e.preventDefault();

                    let formData = new FormData(this);

                    // clear previous errors
                    $('#modal-error-list').html('');
                    $('#modal-success-message').html('');
                    $('.is-invalid').removeClass('is-invalid');

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('lms.store.payment') }}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            $('#modal-success-message').text(response.message ?? 'Payment created successfully!');
                            let successModal = new bootstrap.Modal(document.getElementById('success-alert-modal'));
                            successModal.show();
                            $('#PaymentForm')[0].reset();
                            setTimeout(() => {
                                                window.location.href = "{{ route('lms.referral.payment') }}";
                                            }, 1500);
                        },
                      error: function (xhr) {
                        console.log(xhr.responseJSON);

                        let errorHtml = '';
                        let modalTitle = 'An Unexpected Error Occurred!';

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            errorHtml = '<ul class="list-unstyled text-start">';

                            $.each(errors, function (field, messages) {
                                let filtered = messages.filter(msg => !msg.toLowerCase().includes('required'));
                                if (filtered.length > 0) {
                                    errorHtml += '<li>' + filtered[0] + '</li>';
                                }
                            });

                            errorHtml += '</ul>';
                            if ($(errorHtml).text().trim().length > 0) {
                                $('#modal-error-list').html(errorHtml);
                                let errorModal = new bootstrap.Modal(document.getElementById('danger-alert-modal'));
                                errorModal.show();
                            }

                        } else {

                            let errorMsg = xhr.responseJSON?.error || xhr.responseJSON?.message || 'Something went wrong';
                            $('#modal-error-list').html('<p>' + errorMsg + '</p>');
                            let errorModal = new bootstrap.Modal(document.getElementById('danger-alert-modal'));
                            errorModal.show();
                        }
                    }
                });
            });
        });
</script>
@endpush

    
@endsection 
