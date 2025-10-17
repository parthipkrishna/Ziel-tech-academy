<!-- Edit Modal-->
<div class="modal fade" id="bs-lmsEditUser-modal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="editUserLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editUserLabel{{ $user->id }}">Edit User</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" id="userForm" method="POST" action="{{ route('lms.update.user', $user->id) }}" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" id="name" placeholder="Name" >
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" id="email" placeholder="Email"  autocomplete="off">
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="phone">Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control" id="phone" placeholder="Phone Number">
                                @error('phone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="status">Enable</label><br>
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" name="status" id="switch{{ $user->id }}" data-switch="success" value="1" @if($user->status) checked @endif />
                                <label for="switch{{ $user->id }}" data-on-label="" data-off-label=""></label>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label" for="password">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" name="password" class="form-control" id="password" placeholder="Leave blank to keep current password">
                                    <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="role">Role</label>
                                <select name="role" class="form-control" id="role" required>
                                    <option value="">Select Role</option>
                                    @foreach (getLmsRoles() as $role)
                                        @if ($role->role_name != 'Student')
                                            <option value="{{ $role->id }}" 
                                                {{ $user->role?->role_id == $role->id ? 'selected' : '' }}>
                                                {{ $role->role_name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('role')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Upload Image</label>
                                <input type="file" name="profile_image" class="form-control">
                                {{-- @if ($user->profile_image)
                                    <img src="{{ asset($user->profile_image) }}" alt="Profile Image" width="100" class="mt-2">
                                @endif --}}
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Current Image</label><br>
                                @if ($user->profile_image)
                                    <img src="{{ env('STORAGE_URL') . '/' . $user->profile_image }}" class="me-2 img-fluid avatar-xl">
                                @else
                                    <span class="small text-danger">No Image</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="type" value="lms">
                    <div class="text-start">
                        <button type="reset" class="btn btn-danger">Reset</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
