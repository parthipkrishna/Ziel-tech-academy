@extends('layouts.dashboard')
@section('roles')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Users</a></li>
                        <li class="breadcrumb-item active">Roles</li>
                    </ol>
                </div>
                <h4 class="page-title">Roles</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('roles-permissions', 'create'))
                        <div class="col-sm-5">
                            {{-- <a href="{{ route('users.add') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a> --}}
                            <a href="{{ route('admin.roles.create') }}" class="btn btn-danger mb-2">
                                <i class="mdi mdi-square-edit-outline"></i> Add
                            </a>
                        </div>
                        @endif
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered table-borderless table-hover w-100 dt-responsive nowrap" id="products-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Role Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                                <tbody>
                                    @foreach ($roles as $key => $user)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $user->role_name }}</td>                                        
                                        <td>                        
                                            @if (!$user->system_reserved)
                                            @if(auth()->user()->hasPermission('roles-permissions', 'edit'))
                                                <a href="{{ route('admin.roles.edit', $user->id) }}" class="action-icon">
                                                    <i class="mdi mdi-square-edit-outline"></i>
                                                </a>
                                            @endif
                                            @if(auth()->user()->hasPermission('roles-permissions', 'delete'))
                                                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal{{ $user->id }}">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                            @endif
                                            @else
                                            <span class="bg-white px-2 py-1 rounded" title="System Reserved - Cannot Edit/Delete">
                                                <i class="mdi mdi-lock-outline" style="color: rgba(121, 115, 115, 0.6); font-size: 24px;"></i>
                                            </span>
                                        @endif
                                        </td>
                                    </tr>
                                    <!-- Delete Alert Modal  -->
                                    <div id="delete-alert-modal{{ $user->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-body p-4">
                                                    <div class="text-center">
                                                        <i class="ri-information-line h1 text-info"></i>
                                                        <h4 class="mt-2">Heads up!</h4>
                                                        <p class="mt-3">Do you want to delete this Role?</p>
                                                        <form action="{{ route('admin.roles.delete', $user->id) }}" method="POST">
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
@endsection