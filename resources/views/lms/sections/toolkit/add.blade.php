
@extends('lms.layout.layout')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Tool Kits</a></li>
                        <li class="breadcrumb-item active">Add Tool Kit</li>
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
                    <h4 class="header-title mb-3">Add Tool Kit</h4>
                    
                    <div class="tab-content">
                        <div class="tab-pane show active" id="custom-styles-preview">
                            <form class="needs-validation" id="toolKitForm" method="POST" action="{{ route('lms.toolkits.store') }}" validate enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <!-- Name -->
                                        <div class="mb-3">
                                            <label class="form-label" for="toolKitName">Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" id="toolKitName" placeholder="Enter the tool kit name" value="{{ old('name') }}" required>
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <!-- Course -->
                                        <div class="mb-3">
                                            <label class="form-label" for="courseId">Course <span class="text-danger">*</span></label>
                                            <select name="course_id" class="form-control" id="courseId" required>
                                                <option value="">Select a course</option>
                                                @foreach ($courses as $course)
                                                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('course_id')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <!-- Price -->
                                        <div class="mb-3">
                                            <label class="form-label" for="price">Price</label>
                                            <input type="number" step="0.01" min="0" name="price" class="form-control" id="price" placeholder="Enter price" value="{{ old('price') }}" required>
                                            @error('price')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <!-- Offer Price -->
                                        <div class="mb-3">
                                            <label class="form-label" for="offerPrice">Offer Price</label>
                                            <input type="number" step="0.01" min="0" name="offer_price" class="form-control" id="offerPrice" placeholder="Enter offer price" value="{{ old('offer_price') }}">
                                            @error('offer_price')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>                                      
                                    </div>
                                    <div class="col-lg-6">
                                        <!-- Description -->
                                        <div class="mb-3">
                                            <label class="form-label" for="description">Description</label>
                                            <textarea name="description" class="form-control rich-text" id="description" placeholder="Enter description">{{ old('description') }}</textarea>
                                            @error('description')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="shortDescription">Short Description</label>
                                            <textarea name="short_description" class="form-control rich-text" id="shortDescription" placeholder="Enter short description">{{ old('short_description') }}</textarea>
                                            @error('short_description')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <!-- Media Upload -->
                                        <div class="mb-3">
                                            <label class="form-label" for="media">Media Files</label>
                                            <input type="file" name="media[]" class="form-control" id="media" multiple>
                                            @error('media.*')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <!-- Is Enabled -->
                                        <div class="mb-3">
                                            <label class="form-label" for="is_enabled">Is Enabled</label><br/>
                                            <input type="checkbox" name="is_enabled" id="switch_is_enabled" value="1" {{ old('is_enabled', 1) ? 'checked' : '' }} data-switch="success" onchange="this.value = this.checked ? 1 : 0;" />
                                            <label for="switch_is_enabled" data-on-label="" data-off-label=""></label>
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

    <!-- Success Modal -->
    <div id="success-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content modal-filled bg-success">
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="ri-check-line h1"></i>
                        <h4 class="mt-2">Success!</h4>
                        <p class="mt-3" id="modal-success-message"></p>
                        <button type="button" class="btn btn-light my-2" data-bs-dismiss="modal">Continue</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="danger-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content modal-filled bg-danger">
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="ri-close-circle-line h1"></i>
                        <h4 class="mt-2">Error!</h4>
                        <div id="modal-error-list"></div>
                        <button type="button" class="btn btn-light my-2" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#toolKitForm').submit(function (e) {
                e.preventDefault();

                let formData = new FormData(this);

                // Clear previous errors
                $('#modal-error-list').html('');
                $('#modal-success-message').html('');
                $('.is-invalid').removeClass('is-invalid');

                // Client-side validation for offer_price < price
                const price = parseFloat($('#price').val()) || 0;
                const offerPrice = parseFloat($('#offerPrice').val()) || 0;
                if (offerPrice > 0 && price > 0 && offerPrice >= price) {
                    $('#offerPrice').addClass('is-invalid');
                    $('#modal-error-list').html('<ul class="list-unstyled text-start"><li>Offer price must be less than price.</li></ul>');
                    let errorModal = new bootstrap.Modal(document.getElementById('danger-alert-modal'));
                    errorModal.show();
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: "{{ route('lms.toolkits.store') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.status) {
                            $('#modal-success-message').text(response.message ?? 'Tool Kit created successfully!');
                            let successModal = new bootstrap.Modal(document.getElementById('success-alert-modal'));
                            successModal.show();
                            $('#toolKitForm')[0].reset();
                            setTimeout(() => {
                                window.location.href = "{{ route('lms.toolkits.index') }}";
                            }, 1500);
                        } else {
                            $('#modal-error-list').html('<p>' + (response.message || 'Something went wrong') + '</p>');
                            let errorModal = new bootstrap.Modal(document.getElementById('danger-alert-modal'));
                            errorModal.show();
                        }
                    },
                    error: function (xhr) {
                        let errorHtml = '';
                        let modalTitle = 'An Unexpected Error Occurred!';

                        if (xhr.status !== 422) {
                            let errorMsg = xhr.responseJSON?.error || xhr.responseJSON?.message || 'Something went wrong. Please check the logs.';
                            errorHtml = '<p>' + errorMsg + '</p>';
                            console.error(xhr.responseText);

                            $('#danger-alert-modal .modal-body h4').text(modalTitle);
                            $('#modal-error-list').html(errorHtml);
                            let errorModal = new bootstrap.Modal(document.getElementById('danger-alert-modal'));
                            errorModal.show();
                        }
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editorConfiguration = {
                toolbar: [
                    'heading', 
                    '|', 
                    'bold', 
                    'italic', 
                    '|', 
                    'bulletedList', 
                    'numberedList', 
                    '|', 
                    'undo', 
                    'redo'
                ]
            };
            document.querySelectorAll('.rich-text').forEach((textarea) => {
                ClassicEditor
                    .create(textarea, editorConfiguration)
                    .catch(error => {
                        console.error(error);
                    });
            });
        });
    </script>
@endsection