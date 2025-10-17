    @extends('layouts.dashboard')
    @section('list-user')

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Users</a></li>
                            <li class="breadcrumb-item active">Users</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Users</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            @if(auth()->user()->hasPermission('users', 'create'))
                            <div class="col-sm-5">
                                <a href="{{ route('admin.users.create') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                            </div>
                            @endif
                            <div class="col-sm-7">
                            </div><!-- end col-->
                        </div>
                        <div class="table-responsive">
                            <table class="table table-centered table-borderless table-hover w-100 dt-responsive nowrap" id="products-datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Contact No.</th>
                                        <th>Status</th>
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user_main as $user)
                                        <tr>
                                            <td class="table-user">
                                                @if ($user->profile_image)
                                                    <img src="{{ env('STORAGE_URL') . '/' . $user->profile_image }}" class="me-2 rounded-circle">
                                                @else
                                                    <span class="small text-danger">No Image</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td><span class="fw-semibold">{{ $user->phone }}</span></td>
                                            <td>
                                            <div>
                                                <input type="checkbox" id="switch{{ $user->id }}" 
                                                    data-id="{{ $user->id }}" 
                                                    class="status-toggle" 
                                                    {{ $user->status == 1 ? 'checked' : '' }}  
                                                    data-switch="success"/>
                                                <label for="switch{{ $user->id }}" data-on-label="Yes" data-off-label="No" class="mb-0 d-block" style="cursor: pointer;"></label>
                                            </div>
                                            </td>
                                            <td>
                                                @if($user->roles->isNotEmpty())
                                                    <button type="button" class="btn rounded-pill" style="background-color:rgb(35, 91, 126); color: white;">
                                                        {{ $user->roles->pluck('role_name')->join(', ') }}
                                                    </button>
                                                @endif
                                            </td>
                                            <td>
                                                @if(auth()->user()->hasPermission('users', 'edit'))
                                                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-editUser-modal{{ $user->id }}">
                                                    <i class="mdi mdi-square-edit-outline"></i>
                                                </a>
                                                @endif
                                                @if(auth()->user()->hasPermission('users', 'delete'))
                                                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal{{ $user->id }}">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                                @endif
                                            </td>
                                        </tr>

                                        <!-- Edit Modal-->
                                        <div class="modal fade" id="bs-editUser-modal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="editUserLabel{{ $user->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="editUserLabel{{ $user->id }}">Edit User</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="row">
                                                                <!-- Center Name -->
                                                                <div class="col-lg-6">
                                                                    <div class="mb-3">
                                                                        <label for="center_name" class="form-label">Name</label>
                                                                        <input type="text" class="form-control" id="center_name" name="name" value="{{ $user->name }}">
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for="phone" class="form-label">Phone Number</label>
                                                                        <input type="text" class="form-control" id="phone" name="phone" value="{{ $user->phone }}">
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for="status" class="form-label">Status: </label></br/>
                                                                        <input type="hidden" name="status" id="hidden_status_{{ $user['id'] }}" value="{{ $user['status'] }}">
                                                                        <!-- Use array syntax to access the id -->
                                                                        <input type="checkbox" class="status-toggle" id="status-toggle-{{ $user['id'] }}" data-id="{{ $user['id'] }}" value="1"  
                                                                            {{ $user['status'] == 1 ? 'checked' : '' }} data-switch="success" />
                                                                        <label for="status-toggle-{{ $user['id'] }}" data-on-label="" data-off-label=""></label>
                                                                    </div>
                                                                </div>

                                                                <!-- Address -->
                                                                <div class="col-lg-6">
                                                                    <div class="mb-3">
                                                                        <label for="email" class="form-label">Email</label>
                                                                        <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}">
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for="image" class="form-label">Upload New Image</label>
                                                                        <input type="file" name="profile_image" class="form-control">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="example-select" class="form-label">User Role</label>
                                                                        <select class="form-select" id="example-select" name="user_role" required>
                                                                            <option value="">Select option</option>
                                                                            @foreach ($roles as $item)
                                                                                <option value="{{ $item->id }}" {{ (isset($user->role) && $user->role->role_id == $item->id) ? 'selected' : '' }}>
                                                                                    {{ $item->role_name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
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

                                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                                        </form>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div><!-- /.modal -->

                                        <!-- Delete Alert Modal  -->
                                        <div id="delete-alert-modal{{ $user->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-body p-4">
                                                        <div class="text-center">
                                                            <i class="ri-information-line h1 text-info"></i>
                                                            <h4 class="mt-2">Heads up!</h4>
                                                            <p class="mt-3">Do you want to delete this User?</p>
                                                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger my-2">Delete</button>
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div><!-- /.modal -->

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>
        <!-- end row -->
        <script>
            $(document).ready(function () {
                $('.status-toggle').change(function () {
                    let userId = $(this).data('id');
                    let status = $(this).is(':checked') ? 1 : 0;

                    // Update hidden input field before form submission
                    $('#hidden_status_' + userId).val(status);

                    // Send AJAX request
                    $.ajax({
                        url: "{{ route('admin.users.update', ':id') }}".replace(':id', userId),
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            status: status
                        }
                    });
                });
            });
        </script>
    @endsection
