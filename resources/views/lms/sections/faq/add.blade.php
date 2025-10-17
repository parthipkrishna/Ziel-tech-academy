@extends('lms.layout.layout')
@section('add-faq')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">FAQs</a></li>
                        <li class="breadcrumb-item active">Add FAQ</li>
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
                    <h4 class="header-title mb-3">Add FAQs</h4>
                    
                    <div class="tab-content">
                        <div class="tab-pane show active" id="custom-styles-preview">
                        <form class="needs-validation" id="faqForm" method="POST" action="{{ route('lms.store.faq') }}" validate>
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <!-- Question -->
                                        <div class="mb-3">
                                            <label class="form-label" for="faqQuestion">Question <span class="text-danger">*</span></label>
                                            <textarea name="question" class="form-control" id="faqQuestion" placeholder="Enter the FAQ question">{{ old('question') }}</textarea>
                                            @error('question')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Answer -->
                                        <div class="mb-3">
                                            <label class="form-label" for="faqAnswer">Answer <span class="text-danger">*</span></label>
                                            <textarea name="answer" class="form-control" id="faqAnswer" placeholder="Enter the FAQ answer" >{{ old('answer') }}</textarea>
                                            @error('answer')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- is_enable -->
                                        <div class="mb-3">
                                            <label class="form-label" for="is_enable">Is Enable</label><br/>
                                            <input type="checkbox" name="is_enable" id="switch_is_enable" value="1" checked data-switch="success" onchange="this.value = this.checked ? 1 : 0;" />
                                            <label for="switch_is_enable" data-on-label="" data-off-label=""></label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Buttons -->
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
    <script>
        $(document).ready(function () {
            $('#faqForm').submit(function (e) {
                e.preventDefault();

                let formData = new FormData(this);

                // clear previous errors
                $('#modal-error-list').html('');
                $('#modal-success-message').html('');
                $('.is-invalid').removeClass('is-invalid');

                $.ajax({
                    type: 'POST',
                    url: "{{ route('lms.store.faq') }}", 
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#modal-success-message').text(response.message ?? 'FAQ created successfully!');
                        let successModal = new bootstrap.Modal(document.getElementById('success-alert-modal'));
                        successModal.show();
                        $('#faqForm')[0].reset(); 
                         setTimeout(() => {
                                    window.location.href = "{{ route('lms.faqs') }}";
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
