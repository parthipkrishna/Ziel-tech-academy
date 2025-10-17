@extends('lms.layout.layout')
@section('add-banners')

    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Banners</a></li>
                        <li class="breadcrumb-item active">Add Banner</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Banner Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Add Banner</h4>

                    @if ($message = session()->get('message'))
                        <div class="alert alert-success text-center w-75">
                            <h6 class="fw-bold">{{ $message }}...</h6>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger text-center w-75">
                            @foreach ($errors->all() as $error)
                                <h6 class="fw-bold">{{ $error }}</h6>
                            @endforeach
                        </div>
                    @endif

                    <form id="BannersForm" method="POST" action="{{ route('lms.store.banner') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">

                                <div class="mb-3">
                                    <label for="image" class="form-label">Upload Image</label>
                                    <input type="file" name="image" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"> Type</label>
                                    <select name="type" class="form-control" id="type" required>
                                        <option value="">Select option</option>
                                        @foreach($types as $key => $label)
                                            <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <p class="small text-danger">{{$message}}</p>
                                    @enderror
                                </div>

                                <!-- course dropdown -->
                                <div class="mb-3 related-course-field" style="display: none;">
                                    <label class="form-label">Course</label>
                                    <select name="courseSelect" class="form-control" id="courseSelect">
                                        <option value="">Select option</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" 
                                                {{ old('type') === 'course' && old('related_id') == $course->id ? 'selected' : '' }}>
                                                {{ $course->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Toolkit input -->
                                <div class="mb-3 related-toolkit-field" style="display: none;">
                                    <label class="form-label">ToolKit</label>
                                    <select name="toolkitSelect" class="form-control" id="toolkitSelect">
                                        <option value="">Select option</option>
                                        @if($toolkits->isEmpty())
                                            <option value="" disabled>No toolkits available</option>
                                        @else
                                            @foreach($toolkits as $toolkit)
                                                <option value="{{ $toolkit->id }}" 
                                                    {{ old('type') === 'toolkit' && old('related_id') == $toolkit->id ? 'selected' : '' }}>
                                                    {{ $toolkit->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <!-- hidden actual field -->
                                <input type="hidden" name="related_id" id="related_id">
                                @error('related_id')
                                    <p class="small text-danger">{{$message}}</p>
                                @enderror
                                
                            </div>
                            <div class="col-lg-6">

                                <div class="mb-3">
                                    <label for="example-textarea" class="form-label">Short Description</label>
                                    <textarea class="form-control" name="short_description"  id="example-textarea" rows="5">{{ old('short_description') }}</textarea>
                                    @error('short_description')
                                        <p class="small text-danger">{{$message}}</p>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status: </label></br/>
                                        <input type="hidden" name="status" value="0">
                                    <input  type="checkbox" name="status"  id="switch3"  value="1"  checked  data-switch="success" onchange="this.value = this.checked ? 1 : 0;" />
                                    <label for="switch3" data-on-label="" data-off-label=""></label>
                                </div>

                            </div>
                        </div>
                        
                        <div class="text-start">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                function toggleFields() {
                    const selectedType = $('#type').val();
                    $('.related-course-field, .related-toolkit-field').hide();
                    $('#related_id').val('');

                    if (selectedType === 'course') {
                        $('.related-course-field').show();
                        $('#related_id').val($('#courseSelect').val());
                    } else if (selectedType === 'toolkit') {
                        $('.related-toolkit-field').show();
                        $('#related_id').val($('#toolkitSelect').val());
                    }
                }

                // Keep hidden field in sync
                $('#courseSelect').on('change', function () {
                    $('#related_id').val($(this).val());
                    $(this).valid();
                });
                $('#toolkitSelect').on('change', function () { // Corrected here
                    $('#related_id').val($(this).val());
                    $(this).valid();
                });

                $('#type').on('change', function () {
                    toggleFields();
                    $('#courseSelect, #toolkitSelect').valid(); // Corrected here
                });

                toggleFields(); // Initialize fields on load
                 $.validator.addMethod('maxFileSize', function (value, element, maxSize) {
                    if (!value) return true; // no file selected
                    return element.files[0].size <= maxSize;
                }, 'File must be less than 2 MB.');

                const validator = $("#BannersForm").validate({
                    ignore: [],
                    rules: {
                        image: {
                            required: true,
                            imageExtension: true,
                            maxFileSize: 2 * 1024 * 1024
                        },
                        type: {
                            required: true
                        },
                        courseSelect: {
                            required: {
                                depends: function () {
                                    return $('#type').val() === 'course' && $('.related-course-field').is(':visible');
                                }
                            }
                        },
                        toolkitInput: {
                            required: {
                                depends: function () {
                                    return $('#type').val() === 'toolkit' && $('.related-toolkit-field').is(':visible');
                                }
                            }
                        },
                        short_description: {
                            maxlength: 255
                        }
                    },
                    messages: {
                        image: {
                            required: "Please upload an image",
                            imageExtension: "Please upload a valid image (jpg, jpeg, png, gif, webp).",
                            maxFileSize: "Image size must be less than 2 MB."
                        },
                        type: {
                            required: "Please select a banner type"
                        },
                        courseSelect: {
                            required: "Please select a course"
                        },
                        toolkitInput: {
                            required: "Please enter a link"
                        },
                        short_description: {
                            maxlength: "Maximum 255 characters allowed"
                        }
                    },
                    errorPlacement: function (error, element) {
                        error.addClass('text-danger');
                        error.insertAfter(element);
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

                // Before submitting: make sure hidden related_id is set correctly
                $("#BannersForm").on('submit', function () {
                    const type = $('#type').val();
                    if(type === 'course'){
                        $('#related_id').val($('#courseSelect').val());
                    } else if(type === 'toolkit'){
                        $('#related_id').val($('#toolkitSelect').val());
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function () {
                $('#BannersForm').submit(function (e) {
                    e.preventDefault();

                    let formData = new FormData(this);

                    // clear previous errors
                    $('#modal-error-list').html('');
                    $('#modal-success-message').html('');
                    $('.is-invalid').removeClass('is-invalid');

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('lms.store.banner') }}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            $('#modal-success-message').text(response.message ?? 'Banner created successfully!');
                            let successModal = new bootstrap.Modal(document.getElementById('success-alert-modal'));
                            successModal.show();
                            $('#BannersForm')[0].reset();
                            setTimeout(() => {
                                                window.location.href = "{{ route('lms.banners') }}";
                                            }, 1500);
                        },
                        error: function (xhr) {
                            let errorHtml = '';
                            let modalTitle = 'An Unexpected Error Occurred!';

                            if (xhr.status === 413) {
                                errorHtml = '<p>File too large. Please upload an image under 2MB.</p>';
                                modalTitle = 'Upload Error';
                            } 
                            else if (xhr.status !== 422) {
                                let errorMsg = xhr.responseJSON?.error || xhr.responseJSON?.message || 'Something went wrong. Please check the logs.';
                                errorHtml = '<p>' + errorMsg + '</p>';
                                console.error(xhr.responseText);
                            }

                            if (errorHtml) {
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
    @endpush
@endsection
