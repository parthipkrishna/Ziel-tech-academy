@extends('layouts.dashboard')
@section('roles-add')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">User Roles</a></li>
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
                    <h4 class="header-title mb-3">Add User Roles</h4>
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
                                    <form action="{{ route('admin.roles.store') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <!-- Role Name Input -->
                                            <div class="mb-3 col-12">
                                                <label for="role_name" class="form-label">Role Name</label>
                                                <input type="text" class="form-control" id="role_name" name="role_name" 
                                                    value="{{ old('role_name') }}" required placeholder="Enter role name">
                                            </div>
                                            <!-- Permissions Checkboxes -->
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Status: </label></br/>
                                                <input  type="checkbox" name="status"  id="switch3"  value="1"  checked  data-switch="success" onchange="this.value = this.checked ? 1 : 0;" />
                                                <label for="switch3" data-on-label="" data-off-label=""></label>
                                            </div>
                                            <div class="mb-3">
                                                @if ($permissions->isEmpty())
                                                    <div class="alert alert-warning">
                                                        No permissions created.
                                                    </div>
                                                @else
                                                <div class="row mb-2 pb-2 border-bottom">
                                                    <!-- Headings -->
                                                    <div class="col-md-3">
                                                        <label class="form-label"><strong>Sections</strong></label>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <label class="form-label"><strong>Permissions</strong></label>
                                                    </div>
                                                </div>
                                                @php
                                                    $groupedPermissions = $permissions->groupBy('section_name');
                                                @endphp
                                                @foreach ($groupedPermissions as $section => $perms)
                                                    <div class="row mb-3 align-items-start">
                                                        <!-- Section Name (Left) -->
                                                        <div class="col-md-3">
                                                            <span><b>{{ $section }}</b></span>
                                                        </div>
                                                        <!-- Permissions Checkboxes (Right) -->
                                                        <div class="col-md-9">
                                                            <div class="row">
                                                                @foreach ($perms as $permission)
                                                                    <div class="col-md-4">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" 
                                                                                type="checkbox" 
                                                                                name="permissions[]" 
                                                                                value="{{ $permission->id }}" 
                                                                                id="permission_{{ $permission->id }}">
                                                                            <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                                                {{ $permission->permission_name }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        </div>
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
        
@endsection     