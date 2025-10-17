<form class="needs-validation" id="userForm" method="POST" action="{{ route('lms.store.user') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-6">
            <input type="hidden" name="type" value="lms">
            <div class="mb-3">
                <label class="form-label" for="name">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="name" placeholder="Name" required>
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" id="email" class="form-control" placeholder="Email"  required>
                <div class="invalid-feedback d-block" id="email-error"></div>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
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
                <label for="profile_image" class="form-label">Upload Image</label>
                <input type="file" name="profile_image" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label" for="qualifications">Qualifications</label>
                <input type="text" name="qualifications" value="{{ old('qualifications') }}" class="form-control" id="qualifications" placeholder="Qualifications" >
                @error('qualifications')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status: </label></br/>
                <input type="hidden" name="status" value="0">
                <input type="checkbox" name="status" id="switch3" value="1" checked data-switch="success">
                <label for="switch3" data-on-label="" data-off-label=""></label>
            </div>
        </div>

        <div class="col-lg-6">
             <div class="mb-3">
                <label class="form-label" for="phone">Phone Number</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="form-control" placeholder="Phone Number" required>
                <div class="invalid-feedback d-block" id="phone-error"></div>

                @error('phone')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
             

            <div class="mb-3">
                <label class="form-label" for="role">Role</label>
                <select name="role" class="form-control" id="role" required>
                    <option value="">Select Role</option>
                    @foreach (getLmsRoles() as $role)
                        @if ($role->role_name != 'Student')                        
                            <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                        @endif
                    @endforeach
                </select>
                @error('role')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="joined_date">Joined Date</label>
                <input type="date" name="joined_date" value="{{ old('joined_date') }}" class="form-control" id="joined_date" placeholder="Joined Date">
                @error('joined_date')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="age">Age</label>
                <input type="text" name="age" value="{{ old('age') }}" class="form-control" id="age" placeholder="Age" >
                @error('age')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="gender">Gender</label>
                <select name="gender" class="form-control" id="gender">
                    <option value="">Select gender</option>
                    @foreach(genderOptions() as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('gender')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

        </div>
    </div>
    
    <input type="hidden" name="type" value="lms">
    
    <div class="text-start">
        <button type="reset" class="btn btn-danger">Reset</button>
        <button type="submit" class="btn btn-primary">Create</button>
    </div>
</form>
