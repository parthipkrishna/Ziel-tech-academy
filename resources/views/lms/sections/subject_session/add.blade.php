@extends('lms.layout.layout')
@section('add-banners')

    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Subject Session</a></li>
                        <li class="breadcrumb-item active">Add Subject Session</li>
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
                    <h4 class="header-title mb-3">Add Subject Session</h4>

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

                    <form id="SubjectSessionForm" method="POST" action="{{ route('subject-sessions.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <!-- Title -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">Session Title</label>
                                    <input type="text" name="title" class="form-control" id="title" value="{{ old('title') }}" placeholder="Enter session title">
                                    @error('title')
                                        <p class="small text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <!-- Subject -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="subject_id">Subject <span class="text-danger">*</span></label>
                                    <select name="subject_id" id="subject_id" class="form-select" required>
                                        <option value="">Select Subject</option>
                                        @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">
                                    {{ strtoupper($subject->name) }} - {{ $subject->course ? $subject->course->name : '' }}
                                </option>

                                        @endforeach
                                    </select>
                                    @error('subject_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            <div class="col-lg-6">
                                <!-- Description -->
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" name="description" id="description" rows="5" placeholder="Enter session description">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="small text-danger">{{ $message }}</p>
                                    @enderror
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
<script>
    $(document).ready(function () {
    var validator = $("#SubjectSessionForm").validate({
        rules: {
            title: {
                required: true,
                minlength: 3
            },
        },
        messages: {
            title: {
                required: "Session title is required",
                minlength: "Title must be at least 3 characters long"
            },
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

    $("#SubjectSessionForm button[type='submit']").click(function (event) {
        if (!$("#SubjectSessionForm").valid()) {
            validator.focusInvalid();
            event.preventDefault();
        }
    });
});
    $(document).ready(function () {
        $('#SubjectSessionForm').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            // clear old errors
            $('#modal-error-list').html('');
            $('#modal-success-message').html('');
            $('.is-invalid').removeClass('is-invalid');

            $.ajax({
                type: 'POST',
                url: "{{ route('subject-sessions.store') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    $('#modal-success-message').text(response.message);
                    let successModal = new bootstrap.Modal(document.getElementById('success-alert-modal'));
                    successModal.show();
                    $('#SubjectSessionForm')[0].reset();
                    setTimeout(() => {
                                        window.location.href = "{{ route('subject-sessions.index') }}";
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
