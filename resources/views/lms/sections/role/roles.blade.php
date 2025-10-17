@extends('lms.layout.layout')
@section('content')
<div class="content">

    <!-- Start Content-->
    <div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Ziel Tech</a></li>
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
                        @if(auth()->user()->hasPermission('roles.create'))
                        <div class="col-sm-5">
                            {{-- <a href="{{ route('users.add') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a> --}}
                            <a href="javascript:void(0);" class="btn btn-danger mb-2" data-bs-toggle="modal" data-bs-target="#bs-lmsAddRole-modal">
                                <i class="mdi mdi-square-edit-outline"></i> Add
                            </a>
                        </div>
                        @endif
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        <div class="table-responsive">
                            <table class="table table-striped table-centered mb-0 w-100 dt-responsive nowrap" id="lms-roles-datatable">
                                <thead class="table-dark">
                                    <tr>
                                        <!-- <th style="width: 20px;">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="selectAllRoles">
                                                <label class="form-check-label" for="selectAllRoles">&nbsp;</label>
                                            </div>
                                        </th> -->
                                        <th>Role Name</th>
                                        <th style="width: 75px;">Action</th>
                                    </tr>
                                </thead>
                                @include('lms.sections.role.inc.add-role-modal')
                            </table>
                        </div>
                        {{-- Delete Modals (rendered dynamically via ajaxList) --}}
                        <div id="deleteRoleModals"></div>   
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <!-- end row -->
    </div>
</div>
<script>
        function bindDeleteRoleEvent() {
        $('.confirm-delete-role').off('click').on('click', function () {
            let roleId = $(this).data('id');
            let url = '{{ route("lms.roles.delete", ":id") }}'.replace(':id', roleId);

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'POST'
                },
                success: function (response) {
                    $('#deleteRoleModal' + roleId).modal('hide');
                    $('#lms-roles-datatable').DataTable().ajax.reload(null, false);
                },
                error: function () {
                    alert('Something went wrong. Could not delete Role.');
                }
            });
        });
    }

    $(document).ready(function () {
        $('#lms-roles-datatable').DataTable({
            serverSide: true,
            ajax: "{{ route('lms.roles.ajaxList') }}",
            pageLength: 25,
            columns: [
                { data: 'role_name' },
                { data: 'action', orderable: false, searchable: false }
            ],
            order: [[1, 'asc']],
            responsive: true,
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'></i>",
                    next: "<i class='mdi mdi-chevron-right'></i>"
                }
            },
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                bindDeleteRoleEvent();
            }
        });
    });

</script>
@endsection