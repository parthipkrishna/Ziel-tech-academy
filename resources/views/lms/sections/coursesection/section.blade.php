@extends('lms.layout.layout')
@section('list-courses')

<div id="preloader">
    <div id="status">
        <div class="bouncing-loader">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
</div>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Manage Courses</a></li>
                        <li class="breadcrumb-item active">Course Section</li>
                    </ol>
                </div>
                <h4 class="page-title">Course Section</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('course-sections.create'))
                            <div class="col-sm-5">
                                <a href="javascript:void(0);" class="btn btn-danger mb-2 action-icon" data-bs-toggle="modal" data-bs-target="#bs-addCourseSection-modal"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                            </div>
                        @endif
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-centered mb-0 w-100 dt-responsive nowrap" id="course-sections-datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="display:none;">ID</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                        <div class="modal fade" id="bs-addCourseSection-modal" tabindex="-1" role="dialog" aria-labelledby="addCourseSectionLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="addCourseSectionLabel"><I>Add Course Section</I></h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                    </div>
                                    <div class="modal-body">
                                        @if (session('status'))
                                            <div class="alert alert-success">
                                                {{ session('status') }}
                                            </div>
                                        @endif                                

                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                {{ $errors->first() }}
                                            </div>
                                        @endif
                                        <form action="{{ route('lms.store.course.section') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Name</label>
                                                        <input type="text" name="name"  value="{{ old('name') }}" class="form-control"  id="name"  placeholder="Enter Name" required>
                                                        @error('name')
                                                            <p class="small text-danger">{{$message}}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="status" class="form-label">Status: </label></br/>
                                                            <input type="hidden" name="status" value="0">
                                                        <input  type="checkbox" name="status"  id="switch3"  value="1"  checked  data-switch="success" onchange="this.value = this.checked ? 1 : 0;" />
                                                        <label for="switch3" data-on-label="" data-off-label=""></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-start">
                                                <button type="reset" class="btn btn-danger">Reset</button>
                                                <button type="submit" class="btn btn-primary">Create</button>
                                            </div>
                                        </form>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div>
                            <!-- /.modal -->
                        </div>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <script>
         $(document).on('change', '.status-toggle', function () {
        let sectionId = $(this).data('id');
        let isChecked = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: '{{ route("lms.update.section.status", ":id") }}'.replace(':id', sectionId),
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: isChecked
            },
            success: function (response) {
                console.log(response.message);

                // Sync status in the edit modal too
                $('#status_' + sectionId).prop('checked', !!isChecked);
            },
            error: function () {
                alert('Failed to update section status.');
            }
        });
    });
    </script>
    <script>
       function bindDeleteCourseEvent() {
            $('.delete-course-btn').off('click').on('click', function () {
                let courseId = $(this).data('id');
                let url = '{{ route("lms.delete.course.section", ":id") }}'.replace(':id', courseId);


                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'POST'
                    },
                    success: function (response) {
                        // Optional: Close modal if using one
                        $('#delete-alert-modal' + courseId).modal('hide');

                        // Reload DataTable or remove row
                        $('#course-sections-datatable').DataTable().ajax.reload(null, false);
                    },
                    error: function (xhr) {
                        alert(xhr.responseJSON?.message || 'Something went wrong. Could not delete course.');
                    }
                });
            });
        }

    </script>
    <!-- end row -->
     <script>
        $(document).ready(function() {
        $('#course-sections-datatable').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ route('course.sections.ajax.list') }}",
                type: "GET"
            },
            pageLength: 25,
            columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'name', name: 'name' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[0, 'desc']],
            responsive: true,
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'></i>",
                    next: "<i class='mdi mdi-chevron-right'></i>"
                }
            },
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                bindDeleteCourseEvent();
            }
        });
    });
     </script>
@endsection
