@extends('lms.layout.layout')

@section('add-payment')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Payment Gateways</a></li>
                    <li class="breadcrumb-item active">Add Gateway</li>
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
                <h4 class="header-title mb-3">Create Payment Gateway Configuration</h4>

                <form method="POST" action="{{ route('lms.payment-configs.store') }}" id="PaymentConfigForm" class="needs-validation" novalidate>
                    @csrf
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-lg-6">
                            <!-- Gateway Name -->
                            <div class="mb-3">
                                <label class="form-label">Gateway Name <span class="text-danger">*</span></label>
                                <input type="text" name="gateway_name" class="form-control" value="{{ old('gateway_name') }}" required placeholder="e.g. razorpay">
                                @error('gateway_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Display Name -->
                            <div class="mb-3">
                                <label class="form-label">Display Name</label>
                                <input type="text" name="display_name" class="form-control" value="{{ old('display_name') }}" placeholder="Gateway display name">
                            </div>

                            <!-- API Key -->
                            <div class="mb-3">
                                <label class="form-label">API Key</label>
                                <input type="text" name="api_key" class="form-control" value="{{ old('api_key') }}" placeholder="API Key">
                            </div>

                            <!-- Meta JSON -->
                            <div class="mb-3">
                                <label class="form-label">Meta (Optional)</label>
                                <textarea name="meta" class="form-control">{{ old('meta') }}</textarea>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-lg-6">
                            <!-- API Secret -->
                            <div class="mb-3">
                                <label class="form-label">API Secret</label>
                                <input type="text" name="api_secret" class="form-control" value="{{ old('api_secret') }}" placeholder="API Secret">
                            </div>

                            <!-- Webhook Secret -->
                            <div class="mb-3">
                                <label class="form-label">Webhook Secret</label>
                                <input type="text" name="webhook_secret" class="form-control" value="{{ old('webhook_secret') }}" placeholder="Webhook Secret">
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label class="form-label">Status</label><br/>
                                
                                <!-- hidden field ensures "inactive" is always sent when checkbox is unchecked -->
                                <input type="hidden" name="status" value="inactive">

                                <input type="checkbox" name="status" id="status" value="active" data-switch="success"
                                    {{ old('status') === 'active' ? 'checked' : '' }} />
                                <label for="status" data-on-label="Active" data-off-label="Inactive"></label>
                            </div>
                        </div>
                    </div>

                    <!-- Form Buttons -->
                    <div class="text-start">
                        <button type="reset" class="btn btn-danger">Reset</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div> <!-- end col -->
</div> <!-- end row -->
<script>
    $(document).ready(function () {
        var validator = $("#PaymentConfigForm").validate({
            rules: {
                gateway_name: {
                    required: true,
                    minlength: 3
                },
                api_key: {
                    required: true
                },
                api_secret: {
                    required: true
                },
                webhook_secret: {
                    required: true
                },
                meta: {
                    json: true // We'll define a custom method below
                }
            },
            messages: {
                gateway_name: {
                    required: "Gateway name is required",
                    minlength: "Gateway name must be at least 3 characters"
                },
                api_key: {
                    required: "API Key is required"
                },
                api_secret: {
                    required: "API Secret is required"
                },
                webhook_secret: {
                    required: "Webhook Secret is required"
                },
                meta: {
                    json: "Please enter valid JSON"
                }
            },
            errorPlacement: function (error, element) {
                error.addClass("text-danger").insertAfter(element);
            },
            highlight: function (element) {
                $(element).addClass("is-invalid").removeClass("is-valid");
            },
            unhighlight: function (element) {
                $(element).removeClass("is-invalid").addClass("is-valid");
            },
            onkeyup: function (element) {
                $(element).valid();
            },
            onfocusout: function (element) {
                $(element).valid();
            }
        });

        $("#PaymentConfigForm button[type='submit']").click(function (event) {
            if (!$("#PaymentConfigForm").valid()) {
                validator.focusInvalid();
                event.preventDefault();
            }
        });

        // Add custom JSON validator
        $.validator.addMethod("json", function (value, element) {
            if (value.trim() === "") return true;
            try {
                JSON.parse(value);
                return true;
            } catch (e) {
                return false;
            }
        }, "Invalid JSON format.");
    });
    $(document).ready(function () {
        $('#PaymentConfigForm').submit(function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            // clear previous errors
            $('#modal-error-list').html('');
            $('#modal-success-message').html('');
            $('.is-invalid').removeClass('is-invalid');

            $.ajax({
                type: 'POST',
                url: "{{ route('lms.payment-configs.store') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    $('#modal-success-message').text(response.message ?? 'Payment-Config created successfully!');
                    let successModal = new bootstrap.Modal(document.getElementById('success-alert-modal'));
                    successModal.show();
                    $('#PaymentConfigForm')[0].reset();
                    setTimeout(() => {
                                        window.location.href = "{{ route('lms.payment-config.index') }}";
                                    }, 1500);
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorHtml = '<ul class="list-unstyled text-start">';
                        $.each(errors, function (field, messages) {
                            errorHtml += '<li>' + messages[0] + '</li>';
                            $('[name="'+field+'"]').addClass('is-invalid');
                        });
                        errorHtml += '</ul>';
                        $('#modal-error-list').html(errorHtml);
                    } else {
                        $('#modal-error-list').html('<p>' + (xhr.responseJSON.message || 'Something went wrong') + '</p>');
                    }
                    let errorModal = new bootstrap.Modal(document.getElementById('danger-alert-modal'));
                    errorModal.show();
                }
        });
    });
});
</script>


@endsection
