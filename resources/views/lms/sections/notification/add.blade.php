@extends('lms.layout.layout')
@section('add-notification')

    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Notifications</a></li>
                        <li class="breadcrumb-item active">Add Notification</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Add Notification</h4>

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

                    <form id="NotificationsForm" method="POST" action="{{ route('lms.store.notification') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">

                                <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="title" value="{{ old('title') }}" class="form-control" required>
                                    @error('title')
                                        <p class="small text-danger">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Link</label>
                                    <input type="text" name="link" value="{{ old('link') }}" class="form-control">
                                    @error('link')
                                        <p class="small text-danger">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Type</label>
                                    <select name="type" class="form-control" required>
                                        <option value="">Select option</option>
                                        @foreach($types as $key => $label)
                                            <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <p class="small text-danger">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Category Type</label>
                                    <select name="category_type" class="form-control" id="category_type" required>
                                        <option value="">Select option</option>
                                        @foreach($categoryTypes as $key => $label)
                                            <option value="{{ $key }}" {{ old('category_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_type')
                                        <p class="small text-danger">{{$message}}</p>
                                    @enderror
                                </div>

                                <div id="studentSelector" class="mb-3" style="display: none;">
                                    <label class="form-label">Students</label>                                                 
                                    <select class="select2 form-control select2-multiple" name="student_ids[]" multiple data-placeholder="Choose students">
                                        <optgroup label="Select students">
                                            @foreach ($students as $item)
                                                <option value="{{ $item->id }}">{{ $item->admission_number }}-{{ $item->first_name }}</option>
                                            @endforeach                                               
                                        </optgroup>
                                    </select>                                           
                                </div>
                                
                                <div id="batchSelector" class="mb-3" style="display: none;">
                                    <label class="form-label">Batches</label>                                                 
                                    <select class="select2 form-control select2-multiple" name="batch_ids[]" multiple data-placeholder="Choose batches">
                                        <optgroup label="Select batches">
                                            @foreach ($batches as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach                                               
                                        </optgroup>
                                    </select>                                           
                                </div>                                
                                
                            </div>
                            <div class="col-lg-6">

                                <div class="mb-3">
                                    <label for="example-textarea" class="form-label">Body</label>
                                    <textarea class="form-control" name="body"  id="example-textarea" rows="3" required>{{ old('body') }}</textarea>
                                    @error('body')
                                        <p class="small text-danger">{{$message}}</p>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Extra Info</label>
                                    <textarea name="extra_info" class="form-control" rows="3"></textarea>
                                    @error('extra_info')
                                        <p class="small text-danger">{{$message}}</p>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="image" class="form-label">Upload Image</label>
                                    <input type="file" name="image" class="form-control">
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
            document.addEventListener("DOMContentLoaded", function () {
                const categoryTypeSelect = document.getElementById("category_type");
                const studentSelector = document.getElementById("studentSelector");
                const batchSelector = document.getElementById("batchSelector");

                function toggleSelectors() {
                    const selectedValue = categoryTypeSelect.value;

                    studentSelector.style.display = (selectedValue === "student") ? "block" : "none";
                    batchSelector.style.display = (selectedValue === "batch") ? "block" : "none";
                }

                categoryTypeSelect.addEventListener("change", toggleSelectors);

                // Initialize once on page load
                toggleSelectors();

                // If you're using Select2
                $('.select2').select2();
            });
            $(document).ready(function () {
                $('#NotificationsForm').submit(function (e) {
                    e.preventDefault();

                    let formData = new FormData(this);

                    // clear previous errors
                    $('#modal-error-list').html('');
                    $('#modal-success-message').html('');
                    $('.is-invalid').removeClass('is-invalid');

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('lms.store.notification') }}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            $('#modal-success-message').text(response.message ?? 'Notification created successfully!');
                            let successModal = new bootstrap.Modal(document.getElementById('success-alert-modal'));
                            successModal.show();
                            $('#NotificationsForm')[0].reset();
                             setTimeout(() => {
                                    window.location.href = "{{ route('lms.notifications') }}";
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
    @endpush
@endsection
