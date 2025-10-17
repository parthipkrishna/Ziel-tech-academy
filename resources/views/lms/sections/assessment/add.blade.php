@extends('lms.layout.layout')

@section('add-banners')
<!-- Page Title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Exams</a></li>
                    <li class="breadcrumb-item active">Add Exam</li>
                </ol>
            </div>
            <h4 class="page-title">Add Exam</h4>
        </div>
    </div>
</div>

<!-- Exam Form -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                @if ($message = session()->get('message'))
                    <div class="alert alert-success text-center w-75">
                        <h6 class="fw-bold">{{ $message }}</h6>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger text-center w-75">
                        @foreach ($errors->all() as $error)
                            <h6 class="fw-bold">{{ $error }}</h6>
                        @endforeach
                    </div>
                @endif

                <form id="ExamForm" method="POST" action="{{ route('lms.assessment.store') }}">
                    @csrf
                    <div class="row">
                        
                        <!-- Exam Name -->
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Exam Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            @error('name')
                                <p class="small text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subject -->
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <select name="subject_id" class="form-control" required>
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <p class="small text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                         <!-- Subject Session -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="session_id">Subject Session <span class="text-danger">*</span></label>
                            <select name="subject_session_id" id="session_id" class="form-select" required>
                                <option value="">Select Session</option>
                                @foreach($sessions as $session)
                                    <option value="{{ $session->id }}">{{ $session->title }}</option>
                                @endforeach
                            </select>
                            @error('session_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Batch -->
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Batch <span class="text-danger">*</span></label>
                            <select name="batch_id" class="form-control" required>
                                <option value="">Select Batch</option>
                                @foreach($batches as $batch)
                                    <option value="{{ $batch->id }}" {{ old('batch_id') == $batch->id ? 'selected' : '' }}>
                                        {{ $batch->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('batch_id')
                                <p class="small text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Duration -->
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Duration (Minutes) <span class="text-danger">*</span></label>
                            <input type="number" name="duration" class="form-control" value="{{ old('duration') }}" min="1">
                            @error('duration')
                                <p class="small text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Total Marks -->
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Total Marks <span class="text-danger">*</span></label>
                            <input type="number" name="total_marks" class="form-control" value="{{ old('total_marks') }}" min="1">
                            @error('total_marks')
                                <p class="small text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Minimum Passing Marks -->
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Minimum Passing Marks <span class="text-danger">*</span></label>
                            <input type="number" name="minimum_passing_marks" class="form-control" value="{{ old('minimum_passing_marks') }}" min="0">
                            @error('minimum_passing_marks')
                                <p class="small text-danger">{{ $message }}</p>
                            @enderror
                        </div>


                        <!-- Short Description -->
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Short Description</label>
                            <input type="text" name="short_description" class="form-control" value="{{ old('short_description') }}">
                            @error('short_description')
                                <p class="small text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="col-lg-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="Scheduled" {{ old('status') == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="Ongoing" {{ old('status') == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="Completed" {{ old('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <p class="small text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Full Description -->
                        <div class="col-12 mb-3">
                            <label class="form-label">Full Description</label>
                            <textarea name="description" class="form-control" rows="5">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="small text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="col-12 text-start">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<!-- jQuery Validation -->
<script>
    $(document).ready(function () {
        var validator = $("#ExamForm").validate({
            rules: {
                subject_id: { required: true },
                name: { required: true, minlength: 3 }
            },
            messages: {
                subject_id: {
                    required: "Please select a subject"
                },
                name: {
                    required: "Exam name is required",
                    minlength: "Name must be at least 3 characters"
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

        $("#ExamForm button[type='submit']").click(function (event) {
            if (!$("#ExamForm").valid()) {
                validator.focusInvalid();
                event.preventDefault();
            }
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('#ExamForm').submit(function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            // clear old errors
            $('#modal-error-list').html('');
            $('#modal-success-message').html('');
            $('.is-invalid').removeClass('is-invalid');

            $.ajax({
                type: 'POST',
                url: "{{ route('lms.assessment.store') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    $('#modal-success-message').text(response.message ?? 'Assessment created successfully!');
                    let successModal = new bootstrap.Modal(document.getElementById('success-alert-modal'));
                    successModal.show();

                    $('#ExamForm')[0].reset();

                    setTimeout(() => {
                    window.location.href = "{{ route('lms.assessment.index') }}";
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
                        $('#modal-error-list').html('<p>' + (xhr.responseJSON?.message || 'Something went wrong') + '</p>');
                    }

                    let errorModal = new bootstrap.Modal(document.getElementById('danger-alert-modal'));
                    errorModal.show();
                }
            });
        });
    });
</script>

@endsection
