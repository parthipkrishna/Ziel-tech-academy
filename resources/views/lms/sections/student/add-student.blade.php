@extends('lms.layout.layout')
@section('add-students')


    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Students</a></li>
                        <li class="breadcrumb-item active">Add Students</li>
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
                    <h4 class="header-title mb-3">Add Student</h4>    
                    <div class="row justify-content-center">
                        @if ($message = session()->get('message'))
                            <div class="alert alert-success text-center w-75">
                                <h6 class="text-center fw-bold">{{ $message }}...</h6>
                            </div>
                        @endif  
                    </div>
                                            
                    <div class="tab-content">
                        <div class="tab-pane show active" id="custom-styles-preview">
                            <form class="needs-validation" id="StudentForm" method="POST" action="{{ route('lms.store.student') }}" enctype="multipart/form-data"  novalidate>
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="first_name" class="form-label">First Name</label>
                                            <input type="text" name="first_name"  value="{{ old('first_name') }}" class="form-control"  id="first_name"  placeholder="Enter First Name" required>
                                            @error('first_name')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="text" name="email"  value="{{ old('email') }}" class="form-control"  id="email"  placeholder="Email"  >
                                            @error('email')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="password">Password</label>
                                            <div class="input-group input-group-merge">
                                                <input type="password" name="password" class="form-control" id="password" placeholder="Password" required autocomplete="new-password">
                                                <div class="input-group-text" data-password="false">
                                                    <span class="password-eye"></span>
                                                </div>
                                            </div>
                                            @error('password')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                                            <input type="date" name="date_of_birth"  value="{{ old('date_of_birth') }}" class="form-control"  id="date_of_birth"  placeholder="Enter Date of Birth" >
                                            @error('date_of_birth')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="city" class="form-label">City</label>
                                            <input type="text" name="city"  value="{{ old('city') }}" class="form-control"  id="city"  placeholder="Enter City"  >
                                            @error('city')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="state" class="form-label">State</label>
                                            <input type="text" name="state"  value="{{ old('state') }}" class="form-control"  id="state"  placeholder="Enter State"  >
                                            @error('state')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="admission_date" class="form-label">Admission Date</label>
                                            <input type="date" name="admission_date"  value="{{ old('admission_date') }}" class="form-control"  id="admission_date"  placeholder="Enter Admission Date" >
                                            @error('admission_date')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="guardian_contact" class="form-label">Guardian Contact</label>
                                            <input type="text" name="guardian_contact"  value="{{ old('guardian_contact') }}" class="form-control"  id="guardian_contact"  placeholder="Enter Guardian Contact"  >
                                            @error('guardian_contact')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                    </div>  

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="last_name" class="form-label">Last Name</label>
                                            <input type="text" name="last_name"  value="{{ old('last_name') }}" class="form-control"  id="last_name"  placeholder="Enter Last Name"  >
                                            @error('last_name')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                         <div class="mb-3">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="text" name="phone"  value="{{ old('phone') }}" class="form-control"  id="phone"  placeholder="Enter Phone"  >
                                            @error('phone')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="gender" class="form-label">Gender</label>
                                            <select name="gender" class="form-control" id="gender">
                                                <option value="">Select Gender</option>
                                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('gender')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        

                                        <div class="mb-3">
                                            <label for="example-textarea" class="form-label">Address</label>
                                            <textarea class="form-control" name="address" value="{{ old('address') }}" id="example-textarea" rows="2"></textarea>
                                            @error('address')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3 ">
                                            <label for="profile_photo" class="form-label">Profile Photo</label>
                                            <input type="file" class="form-control" id="profile_photo" name="profile_photo">
                                        </div>

                                        <div class="mb-3">
                                            <label for="zip_code" class="form-label">Zip Code</label>
                                            <input type="text" name="zip_code"  value="{{ old('zip_code') }}" class="form-control"  id="zip_code"  placeholder="Enter Zip Code"  >
                                            @error('zip_code')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="guardian_name" class="form-label">Guardian Name</label>
                                            <input type="text" name="guardian_name"  value="{{ old('guardian_name') }}" class="form-control"  id="guardian_name"  placeholder="Enter Guardian Name"  >
                                            @error('guardian_name')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                    </div>
                                </div> 

                                <!-- Submit Button -->
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
            $('#StudentForm').submit(function (e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('lms.store.student') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#modal-success-message').text(response.message || 'Student registered successfully');
                        var successModal = new bootstrap.Modal(document.getElementById('success-alert-modal'));
                        successModal.show();

                        $('#StudentForm')[0].reset();

                        setTimeout(() => {
                            window.location.href = "{{ route('lms.students') }}";
                        }, 1500);
                    },
                    error: function (xhr) {
                        let errorHtml = '';
                        let modalTitle = 'Validation Error!';

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            errorHtml = '<ul class="list-unstyled text-start">';
                            $.each(errors, function (field, messages) {
                                errorHtml += '<li>' + messages[0] + '</li>';
                            });
                            errorHtml += '</ul>';
                        } else {
                            modalTitle = 'An Unexpected Error Occurred!';
                            let errorMsg = xhr.responseJSON && xhr.responseJSON.message
                                        ? xhr.responseJSON.message
                                        : 'The server encountered an error. Please check the logs.';
                            errorHtml = '<p>' + errorMsg + '</p>';
                        }

                        $('#danger-alert-modal .modal-body h4').text(modalTitle);
                        $('#modal-error-list').html(errorHtml);
                        var errorModal = new bootstrap.Modal(document.getElementById('danger-alert-modal'));
                        errorModal.show();
                    }
                });
            });
        });                     
    </script>
@endsection 
