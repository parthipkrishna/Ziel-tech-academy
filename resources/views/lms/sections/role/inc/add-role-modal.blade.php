<!-- Edit Modal-->
<div class="modal fade" id="bs-lmsAddRole-modal" tabindex="-1" role="dialog" aria-labelledby="lmsAddRole-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" ">Add Role</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('lms.store.role',['type','lms']) }}" method="POST" enctype="multipart/form-data">
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
                                                            name="permission_ids[]"  
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
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.modal -->