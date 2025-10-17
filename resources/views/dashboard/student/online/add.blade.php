@extends('layouts.dashboard')
@section('add-student-enrollment')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Enrollment</a></li>
                        <li class="breadcrumb-item active">Add Enrollment</li>
                    </ol>
                </div>
                <h4 class="page-title">Add Enrollment</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Add Enrollment</h4>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="custom-styles-preview">
                            <form class="needs-validation" id="EnrollmentForm" method="POST" action="{{ route('admin.student.enroll.store') }}" enctype="multipart/form-data"  validate>
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom01">First name</label>
                                            <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" id="name" placeholder="Name" required>
                                            @error('first_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom01">Last Name</label>
                                            <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" id="name" placeholder="Name" required>
                                            @error('last_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom01">Phone Number</label>
                                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" id="validationCustom01" placeholder="Phone Number" required>
                                            @error('phone')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select name="status" class="form-control">
                                                <option value="">Choose a Status</option>
                                                @foreach(\App\Models\StudentEnrollment::getStatusOptions() as $status)
                                                    <option value="{{ $status }}" {{ old('status', $enrollment->status ?? '') == $status ? 'selected' : '' }}>
                                                        {{ ucfirst($status) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustomUsername">Email</label>
                                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" id="validationCustomUsername" placeholder="Email" aria-describedby="inputGroupPrepend" required autocomplete="off">
                                        </div>

                                        <div class="mb-3">
                                            <label for="district" class="form-label">Courses</label>
                                            <select class="form-select" id="example-select" name="course_id" required>
                                                <option value="">Select option</option>
                                                @foreach ($courses as $course)
                                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                </div>
                                <div id="emailError" style="color: red;" class="mb-2"></div>
                                <div class="text-start">
                                    <button type="reset" class="btn btn-danger">Reset</button>
                                    <button type="submit" id="submitBtn" class="btn btn-primary">Create</button>
                                </div>
                            </form>
                        </div> <!-- end preview-->
                    </div> <!-- end tab-content-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
<script>
    $('#EnrollmentForm').on('submit', function(e) {
        e.preventDefault();

        $('#submitBtn').attr('disabled', true);
        $('#emailError').html('');
        $('#errorMessages').html('');

        let formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            success: function(response) {
                if (response.redirect) {
                    window.location.href = response.redirect;
                } else {
                    $('#successMessage').html(response.message);
                    $('#EnrollmentForm')[0].reset();
                }
            },
            error: function(xhr) {
                $('#submitBtn').attr('disabled', false);

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorHtml = '<ul>';
                    $.each(errors, function(key, value) {
                        errorHtml += '<li>' + value[0] + '</li>';
                    });
                    errorHtml += '</ul>';
                    $('#errorMessages').html(errorHtml);
                } else if (xhr.status === 409 && xhr.responseJSON?.message) {
                    $('#emailError').html(xhr.responseJSON.message);
                } else if (xhr.responseJSON?.message) {
                    $('#errorMessages').html('<p>' + xhr.responseJSON.message + '</p>');
                } else {
                    $('#errorMessages').html('<p>Something went wrong. Please try again.</p>');
                }
            }
        });
    });
</script>


@endsection
