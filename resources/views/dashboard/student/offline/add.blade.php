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
                            <form class="needs-validation" id="EnrollmentForm" method="POST" action="{{ route('admin.offline.student.enroll.store') }}" enctype="multipart/form-data"  validate>
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom01">Name</label>
                                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="name" placeholder="Name" required>
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom01">Phone Number</label>
                                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" id="validationCustom01" placeholder="Phone Number" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select name="status" class="form-control">
                                                <option value="">Choose a Status</option>
                                                @foreach(\App\Models\OfflineCourseEnrollment::getStatusOptions() as $status)
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
