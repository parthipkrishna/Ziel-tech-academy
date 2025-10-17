@extends('lms.layout.layout')
@section('add-liveclasses')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Live Classes</a></li>
                        <li class="breadcrumb-item active">Add Live Class</li>
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
                    <h4 class="header-title mb-3">Add Live Class</h4>
                    <div class="row justify-content-center">
                         {{-- Display general messages --}}
                         @if ($message = session()->get('message'))
                            <div class="alert alert-success text-center w-75">
                                <h6 class="text-center fw-bold">{{ $message }}...</h6>
                            </div>
                        @endif
                        {{-- Display validation error messages --}}
                        @if ($errors->any())
                            <div class="alert alert-danger text-center w-75">
                                @foreach ($errors->all() as $error)
                                    <h6 class="text-center fw-bold">{{ $error }}</h6>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="custom-styles-preview">
                            @php
                                $user = auth()->user();
                            @endphp
                            <form class="needs-validation" id="LiveClassForm" method="POST" action="{{ route('lms.store.live.class') }}" enctype="multipart/form-data"  novalidate>
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="name">Name</label>
                                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="name" placeholder="Enter Name" required>
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="meeting_link">Meeting Link</label>
                                            <input type="text" name="meeting_link" value="{{ old('meeting_link') }}" class="form-control" id="meeting_link" placeholder="Enter Meeting Link" required>
                                            @error('meeting_link')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <!-- Subject Session -->
                                        <div class="mb-3">
                                            <label class="form-label" for="session_id">Subject Session <span class="text-danger">*</span></label>
                                            <select name="subject_session_id" id="session_id" class="form-select" required>
                                                <option value="">Select Session</option>
                                                <!-- Sessions populated via JavaScript -->
                                            </select>
                                            @error('session_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="start_time">Start Time</label>
                                            <input type="datetime-local" name="start_time" value="{{ old('start_time') }}" class="form-control" id="start_time" placeholder="Enter Start Time" required>
                                            @error('start_time')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="end_time">End Time</label>
                                            <input type="datetime-local" name="end_time" value="{{ old('end_time') }}" class="form-control" id="end_time" placeholder="Enter End Time" required>
                                            @error('end_time')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="thumbnail_image" class="form-label">Upload Thumbnail Image</label>
                                            <input type="file" name="thumbnail_image" id="thumbnail_image" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Subject</label>
                                            <select name="subject_id" class="form-select" required>
                                                <option value="">-- Select Subject --</option>
                                                @foreach($subjects as $subject)
                                                    <option value="{{ $subject->id }}">
                                                        {{ strtoupper($subject->name) }} {{ $subject->course ? ' - ' . $subject->course->name : '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if($user->hasRole('Admin') || $user->hasRole('Super Admin'))
                                            <div class="mb-3">
                                                <label for="tutor_id" class="form-label">Tutor</label>
                                                <select class="form-select"  name="tutor_id" id="tutor_id" required>
                                                    <option value="">Select option</option>
                                                    @foreach ($tutors as $item)
                                                        <option value="{{ $item->id }}">{{ $item->user->name  }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                        @if($user->hasRole('Tutor') && $user->tutor)
                                            <input type="hidden" name="tutor_id" id="tutor_id" value="{{ $user->tutor->id }}">
                                        @endif

                                        @if(($user->hasRole('Admin') || $user->hasRole('Super Admin')) || $user->hasRole('Tutor'))

                                            <div class="mb-3">
                                                <label for="batch_id" class="form-label">Batch</label>
                                                <select class="form-select"  name="batch_id" id="batch_id" required>
                                                    <option value="">Select option</option>
                                                    @foreach ($batches as $batch)
                                                        <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select class="form-control" name="status" required>
                                                <option value="Pending" selected>Pending</option>
                                                <option value="Ongoing">Ongoing</option>
                                                <option value="Completed">Completed</option>
                                            </select>
                                            @error('status')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="example-textarea" class="form-label">Short Summary</label>
                                            <textarea class="form-control" name="short_summary" id="short_summary" rows="2"></textarea>
                                            @error('short_summary')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="example-textarea" class="form-label">Summary</label>
                                            <textarea class="form-control" name="summary" id="summary" rows="2"></textarea>
                                            @error('summary')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="text-start">
                                    <button type="reset" class="btn btn-danger">Reset</button>
                                    <button type="submit" class="btn btn-primary" id="submitLiveClass">Create</button>
                                </div>
                            </form>
                        </div> <!-- end preview-->
                    </div> <!-- end tab-content-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->



@push('liveclass-scripts')
<script>
$(document).ready(function () {
    // Add custom file size validation rule
    $.validator.addMethod('filesize', function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param);
    }, 'File size must be less than {0} bytes');

    const validator = $("#LiveClassForm").validate({
        ignore: [],
        rules: {
            name: {
                required: true,
                maxlength: 255
            },
            meeting_link: {
                required: true,
                url: true
            },
            start_time: {
                required: true,
                date: true
            },
            end_time: {
                required: true,
                date: true
            },
            subject_id: {
                required: true
            },
            tutor_id: {
                required: function (element) {
                    return $(element).is(':visible');
                }
            },
            batch_id: {
                required: true
            },
            short_summary: {
                maxlength: 500
            },
            summary: {
                maxlength: 5000
            },
            thumbnail_image: {
                extension: "jpg|jpeg|png|webp",
                filesize: 2048000 // 2MB
            }
        },
        messages: {
            name: {
                required: "Name is required",
                maxlength: "Max 255 characters"
            },
            meeting_link: {
                required: "Meeting link is required",
                url: "Enter a valid URL"
            },
            start_time: {
                required: "Start time is required"
            },
            end_time: {
                required: "End time is required"
            },
            subject_id: {
                required: "Please select a subject"
            },
            tutor_id: {
                required: "Please select a tutor"
            },
            batch_id: {
                required: "Please select a batch"
            },
            short_summary: {
                maxlength: "Short summary can have max 500 characters"
            },
            summary: {
                maxlength: "Summary can have max 5000 characters"
            },
            thumbnail_image: {
                extension: "Only jpg, jpeg, png, webp allowed",
                filesize: "Max file size is 2MB"
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
});
</script>
@endpush

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Handle subject change
    $('select[name="subject_id"]').change(function() {
        const subjectId = $(this).val();
        const sessionDropdown = $('#session_id');
        
        // Reset session dropdown
        sessionDropdown.html('<option value="">Select Session</option>');
        
        if (!subjectId) return; // Exit if no subject selected
        
        // Fetch sessions via AJAX
        $.ajax({
            url: "{{ route('get-sessions', ['subject' => ':subjectId']) }}".replace(':subjectId', subjectId),
            type: 'GET',
            success: function(sessions) {
                if (sessions.length > 0) {
                    $.each(sessions, function(key, session) {
                        sessionDropdown.append(
                            `<option value="${session.id}">${session.title}</option>`
                        );
                    });
                } else {
                    sessionDropdown.append(
                        `<option value="">No sessions available</option>`
                    );
                }
            },
            error: function() {
                alert('Error loading sessions');
            }
        });
    });

    // Preselect values if form has old input (after validation error)
    @if(old('subject_id'))
        $('select[name="subject_id"]').val("{{ old('subject_id') }}").trigger('change');
        setTimeout(() => {
            $('#session_id').val("{{ old('session_id') }}");
        }, 500);
    @endif
});
$(document).ready(function () {
    $('#LiveClassForm').submit(function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        // reset old messages
        $('#modal-error-list').html('');
        $('#modal-success-message').html('');
        $('.is-invalid').removeClass('is-invalid');

        $.ajax({
            type: "POST",
            url: "{{ route('lms.store.live.class') }}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#modal-success-message').text(response.message);
                let successModal = new bootstrap.Modal(document.getElementById('success-alert-modal'));
                successModal.show();
                $('#LiveClassForm')[0].reset();
                 setTimeout(() => {
                                    window.location.href = "{{ route('lms.live.classes') }}";
                                }, 1500);
            },
            error: function (xhr) {
                let errorHtml = '';
                let modalTitle = 'An Unexpected Error Occurred!';

                if (xhr.status !== 422) { 
                    let errorMsg = xhr.responseJSON?.error || xhr.responseJSON?.message || 'Unexpected error. Please check the logs.';
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

@endsection
