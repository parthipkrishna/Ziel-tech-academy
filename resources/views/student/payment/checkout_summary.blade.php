@extends('student.layouts.layout')
@section('student-dashboard')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Order Summary</h4>
                </div>
                <div class="card-body">
                    <!-- Course Details -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5>Course Details</h5>
                            <div class="d-flex justify-content-between">
                                <span><strong>{{ $course->name }}</strong></span>
                                <span>₹{{ number_format($courseAmount, 2) }}</span>
                            </div>
                            <small class="text-muted">Duration: {{ $course->total_hours }} hours</small>
                        </div>
                    </div>

                    <!-- Toolkit Details -->
                    @if($toolkit)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5>Toolkit</h5>
                            <div class="d-flex justify-content-between">
                                <span>{{ $toolkit->name }}</span>
                                <span>₹{{ number_format($toolkit->price, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Price Breakdown -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span>Subtotal:</span>
                                <span>₹{{ number_format($courseAmount + ($toolkit->price ?? 0), 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>GST (18%):</span>
                                <span>₹{{ number_format($gstAmount, 2) }}</span>
                            </div>
                            
                            @if($loyaltyInfo['applied_discount'] > 0)
                            <div class="d-flex justify-content-between text-success">
                                <span>Loyalty Points Discount:</span>
                                <span>-₹{{ number_format($loyaltyInfo['applied_discount'], 2) }}</span>
                            </div>
                            @endif
                            
                            <hr>
                            <div class="d-flex justify-content-between fs-5 fw-bold">
                                <span>Total Amount:</span>
                                <span>₹{{ number_format($totalAmount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Loyalty Info -->
                    @if($loyaltyInfo['available_points'] > 0 && $loyaltyInfo['applied_discount'] == 0)
                    <div class="alert alert-info">
                        <small>
                            You have {{ $loyaltyInfo['available_points'] }} loyalty points available. 
                            You can use up to {{ $loyaltyInfo['max_usable_points'] }} points for this purchase.
                        </small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Complete Payment</h5>
                </div>
                <div class="card-body">
                    @if($totalAmount == 0)
                        <!-- Free Course Enrollment -->
                        <form method="POST" action="{{ route('payments-student.confirm-free') }}">
                            @csrf
                            <input type="hidden" name="student_id" value="{{ auth()->user()->studentId }}">
                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                            <input type="hidden" name="transaction_id" value="{{ $payment->transaction_id }}">
                            
                            <button type="submit" class="btn btn-success w-100 py-3">
                                <i class="fas fa-check-circle"></i> Enroll for Free
                            </button>
                            <p class="text-muted text-center mt-2 small">
                                Click to enroll immediately at no cost
                            </p>
                        </form>
                    @else
                        <!-- Razorpay Payment -->
                        <button id="rzp-button" class="btn btn-primary w-100 py-3">
                            <i class="fas fa-lock"></i> Pay ₹{{ number_format($totalAmount, 2) }}
                        </button>                 
                        <p class="text-muted text-center mt-2 small">
                            Secure payment powered by Razorpay
                        </p>
                        <!-- Cancel Payment -->
                        <button type="button" onclick="cancelPayment()" class="btn btn-outline-secondary w-100">
                            Cancel Payment
                        </button>
                    @endif
                </div>
            </div>

            <!-- Payment Security Info -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6><i class="fas fa-shield-alt text-success"></i> Secure Payment</h6>
                    <small class="text-muted">
                        Your payment information is encrypted and secure. We do not store your credit card details.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

@if($totalAmount > 0 && $razorpayOrder)
<!-- Razorpay Payment Script -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('rzp-button').onclick = function(e){
    e.preventDefault();
    
    var options = {
        "key": "{{ $razorpayOrder['payment_key'] }}",
        "amount": "{{ round($totalAmount * 100) }}", // Amount in paise
        "currency": "INR",
        "name": "{{ $razorpayOrder['company_name'] }}",
        "description": "Course Enrollment - {{ $course->name }}",
        "image": "{{ asset('images/logo.png') }}",
        "order_id": "{{ $razorpayOrder['order_id'] }}",
        "handler": function (response){
            // On successful payment
            confirmPaidPayment(response);
        },
        "prefill": {
            "name": "{{ auth()->user()->name }}",
            "email": "{{ auth()->user()->email }}",
            "contact": "{{ auth()->user()->phone ?? '' }}"
        },
        "theme": {
            "color": "#007bff"
        },
        "modal": {
            "ondismiss": function(){
                if(confirm('Are you sure you want to cancel the payment?')) {
                    cancelPayment();
                }
            }
        }
    };

    var rzp = new Razorpay(options);
    rzp.open();
}

function confirmPaidPayment(response) {
    // Create a form and submit to confirm paid endpoint
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("payments-student.confirm-paid") }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    const transactionId = document.createElement('input');
    transactionId.type = 'hidden';
    transactionId.name = 'transaction_id';
    transactionId.value = '{{ $payment->transaction_id }}';
    form.appendChild(transactionId);
    
    const paymentId = document.createElement('input');
    paymentId.type = 'hidden';
    paymentId.name = 'razorpay_payment_id';
    paymentId.value = response.razorpay_payment_id;
    form.appendChild(paymentId);
    
    const signature = document.createElement('input');
    signature.type = 'hidden';
    signature.name = 'signature';
    signature.value = response.razorpay_signature;
    form.appendChild(signature);
    
    document.body.appendChild(form);
    form.submit();
}

function cancelPayment() {
    fetch("{{ route('payments-student.cancel') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            transaction_id: "{{ $payment->transaction_id }}",
            reason: "Cancelled by user"
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.redirect_url) {
            window.location.href = data.redirect_url; // redirect manually
        } else {
            console.error("Cancel failed:", data.message);
        }
    })
    .catch(error => console.error("Cancel error:", error));
}
</script>
@endif
@endsection