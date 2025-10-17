@extends('lms.layout.layout')
@section('content')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Edit Roles</a></li>
                        <li class="breadcrumb-item active">Roles</li>
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
                    <h4 class="header-title mb-3">Edit User Roles & Permissions</h4>
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
                        <div class="tab-pane show active" id="custom-styles-preview"></div>
                        <form action="{{ route('lms.roles.update', $role->id) }}" method="POST" enctype="multipart/form-data" novalidate>
                            @csrf
                            <div class="mb-3">
                                <label for="role_name" class="form-label">Role Name</label>
                                <input type="text" class="form-control" id="role_name" name="role_name" value="{{ $role->role_name }}">
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status: </label></br/>
                                <input type="checkbox" name="status" id="switch3" value="1" {{ $role->status == 1 ? 'checked' : '' }} data-switch="success" onchange="this.value = this.checked ? 1 : 0;" />
                                <label for="switch3" data-on-label="" data-off-label=""></label>
                            </div>

                            <div class="mb-3">
                                <div class="row fw-bold border-bottom pb-2 mb-3">
                                    <div class="col-md-3">Section Name</div>
                                    <div class="col-md-9">Permissions</div>
                                </div>

                                @foreach ($permissions as $section => $perms)
                                    <div class="row mb-3 align-items-start">
                                        <div class="col-md-3 fw-bold pt-2">
                                            {{ ucfirst($section) }}
                                        </div>
                                        <div class="col-md-9">
                                            <div class="row">
                                                @foreach ($perms as $permission)
                                                    @if(is_object($permission))
                                                        <div class="col-md-4">
                                                            <div class="form-check">
                                                                <input class="form-check-input"
                                                                    type="checkbox"
                                                                    name="permission_ids[]"
                                                                    value="{{ $permission->id }}"
                                                                    id="perm{{ $role->id }}_{{ $permission->id }}"
                                                                    {{ in_array($permission->id, $role_permissions[$role->id] ?? []) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="perm{{ $role->id }}_{{ $permission->id }}">
                                                                    {{ $permission->permission_name }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach
                            </div>

                            <div class="text-start mb-3 mx-2">
                                <button type="reset" class="btn btn-danger">Reset</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div> <!-- end tab-content -->
                </div> <!-- end card -->
            </div> <!-- end col -->

@endsection