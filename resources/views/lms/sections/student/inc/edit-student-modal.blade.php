<div class="modal fade" id="bs-editStudent-modal{{ $student->id }}" tabindex="-1" role="dialog" aria-labelledby="editStudentLabel{{ $student->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editStudentLabel{{ $student->id }}">Edit Student</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('lms.update.student', $student->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf   
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" name="first_name"  value="{{ $student->first_name}}" class="form-control"  id="first_name"  placeholder="Enter First Name" >
                            </div>
                            <div class="mb-3">
                                <label for="student_email" class="form-label">Email</label>
                                <input type="text" value="{{ $student->user->email }}" class="form-control" disabled >
                            </div>
                             <div class="mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select name="gender" class="form-control" id="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ $student->gender == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ $student->gender == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ $student->gender == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="state" class="form-label">State</label>
                                <input type="text" name="state"  value="{{ $student->state }}" class="form-control"  id="state"  placeholder="Enter State"  >
                            </div>

                            <div class="mb-3">
                                <label for="admission_date" class="form-label">Admission Date</label>
                                <input type="date" name="admission_date"  value="{{ $student->admission_date }}" class="form-control"  id="admission_date"  placeholder="Enter Admission Date" >
                            </div>

                            <div class="mb-3">
                                <label for="guardian_name" class="form-label">Guardian Name</label>
                                <input type="text" name="guardian_name"  value="{{ $student->guardian_name }}" class="form-control"  id="guardian_name"  placeholder="Enter Guardian Name">
                            </div>

                            <div class="mb-3">
                                <label for="guardian_contact" class="form-label">Guardian Contact</label>
                                <input type="text" name="guardian_contact"  value="{{ $student->guardian_contact }}" class="form-control"  id="guardian_contact"  placeholder="Enter Guardian Contact"  >
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Current Image</label><br>
                                @if ($student->profile_photo)
                                    <img src="{{ env('STORAGE_URL') . '/' . $student->profile_photo }}" class="me-2 img-fluid avatar-xl">
                                @else
                                    <span class="small text-danger">No Image</span>
                                @endif
                            </div>

                        </div>  

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" name="last_name"  value="{{ $student->last_name }}" class="form-control"  id="last_name"  placeholder="Enter Last Name"  >
                            </div>
                             <div class="mb-3">
                                <label for="student_email" class="form-label">Phone</label>
                                <input type="text" value="{{ $student->user->phone }}" class="form-control" disabled >
                            </div>
                            <div class="mb-3">
                                <label for="example-textarea" class="form-label">Address</label>
                                <textarea class="form-control" name="address" id="example-textarea" rows="2">{{ $student->address }}</textarea>
                            </div>

                            <div class="mb-3 ">
                                <label for="profile_photo" class="form-label">Profile Photo</label>
                                <input type="file" class="form-control" id="profile_photo" name="profile_photo">
                            </div>                                                                 

                            <div class="mb-3">
                                <label for="zip_code" class="form-label">Pin Code</label>
                                <input type="text" name="zip_code"  value="{{ $student->zip_code }}" class="form-control"  id="zip_code"  placeholder="Enter Zip Code">
                            </div>

                            <div class="mb-3">
                                <label for="status_{{  $student->id }}" class="form-label">Status: </label></br/>
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" name="status" id="status_{{  $student->id }}" value="1"  {{  $student->status == 1 ? 'checked' : '' }}  data-switch="success" />
                                <label for="status_{{  $student->id }}" data-on-label="" data-off-label=""></label>
                            </div>
                            <div class="mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" name="date_of_birth"  value="{{ $student->date_of_birth }}" class="form-control"  id="date_of_birth"  placeholder="Enter Date of Birth" >
                            </div>
                            <div class="mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" name="city"  value="{{ $student->city }}" class="form-control"  id="city"  placeholder="Enter City"  >
                            </div>
                        </div>
                    </div>                                                                                                                                                        
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->