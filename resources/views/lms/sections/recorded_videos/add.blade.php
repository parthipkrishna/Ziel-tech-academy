@extends('lms.layout.layout')
@section('add-banners')

<!-- Page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Recorded Videos</a></li>
                    <li class="breadcrumb-item active">Add Session</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- End page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3">Add Recorded Video Session</h4>
                <div class="tab-content">
                    <div class="tab-pane show active" id="custom-styles-preview">
                        <form method="POST" action="{{ route('lms.recorded.videos.store') }}" class="needs-validation" id="RecordedVideoForm">
                            @csrf
                            <div class="row">
                                <!-- Subject -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="subject_id">Subject <span class="text-danger">*</span></label>
                                    <select name="subject_id" id="subject_id" class="form-select" required>
                                        <option value="">Select Subject</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}">
                                                {{ strtoupper($subject->name) }} {{ $subject->course ? ' - ' . $subject->course->name : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subject_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- Subject Session -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="session_id">Subject Session <span class="text-danger">*</span></label>
                                    <select name="session_id" id="session_id" class="form-select" required>
                                                <option value="">Select Session</option>
                                                <!-- Sessions populated via JavaScript -->
                                            </select>
                                    @error('session_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- Video -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="video_id">Video <span class="text-danger">*</span></label>
                                    <select name="video_id" id="video_id" class="form-select" required>
                                        <option value="">Select Video</option>
                                        @foreach($videos as $video)
                                            <option value="{{ $video->id }}">{{ $video->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('video_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Is Enabled -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="is_enabled">Is Enabled</label><br>
                                    <input type="checkbox" name="is_enabled" id="switch_is_enabled" value="1" checked data-switch="success" onchange="this.value = this.checked ? 1 : 0;" />
                                    <label for="switch_is_enabled" data-on-label="Yes" data-off-label="No"></label>
                                </div>
                            </div>

                            <!-- Form Buttons -->
                            <div class="text-start">
                                <button type="reset" class="btn btn-danger">Reset</button>
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </form>
                    </div> <!-- end preview -->
                </div> <!-- end tab-content -->
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div><!-- end col -->
</div><!-- end row -->
<script>
    $(document).ready(function () {
        var validator = $("#RecordedVideoForm").validate({
            rules: {
                subject_id: { required: true },
                session_id: { required: true },
                video_id: { required: true },
            },
            messages: {
                subject_id: {
                    required: "Please select a subject"
                },
                session_id: {
                    required: "Please select a session"
                },
                video_id: {
                    required: "Please select a video"
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

        $("#RecordedVideoForm button[type='submit']").click(function (event) {
            if (!$("#RecordedVideoForm").valid()) {
                validator.focusInvalid();
                event.preventDefault();
            }
        });
    });
</script>
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
    $('#RecordedVideoForm').submit(function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        // clear previous errors
        $('#modal-error-list').html('');
        $('#modal-success-message').html('');
        $('.is-invalid').removeClass('is-invalid');

        $.ajax({
            type: 'POST',
            url: "{{ route('lms.recorded.videos.store') }}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#modal-success-message').text(response.message);
                let successModal = new bootstrap.Modal(document.getElementById('success-alert-modal'));
                successModal.show();
                $('#RecordedVideoForm')[0].reset();
                  setTimeout(() => {
                                    window.location.href = "{{ route('lms.recorded.videos.index') }}";
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
