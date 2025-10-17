@extends('lms.layout.layout')
@section('list-banners')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Mobile & App Content Management</a></li>
                        <li class="breadcrumb-item active">Banners</li>
                    </ol>
                </div>
                <h4 class="page-title">Banners</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('banners.create'))
                            <div class="col-sm-5">
                                <a href="{{ route('lms.add.banner') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                            </div>
                        @endif
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-centered mb-0 w-100 dt-responsive nowrap" id="banner-datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="display:none;">ID</th>
                                    <th>Image</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                        @foreach ($banners as $banner)
                        <!-- Edit Modal-->
                        <div class="modal fade" id="bs-editCourse-modal{{ $banner->id }}" tabindex="-1" role="dialog" aria-labelledby="editCourseLabel{{ $banner->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="editCourseLabel{{ $banner->id }}">Edit online Course</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('lms.update.banner', $banner->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3 ">
                                                        <label for="image" class="form-label">Upload Image</label>
                                                        <input type="file" class="form-control" id="image" name="image">
                                                    </div>                                                                 
                                                    <div class="mb-3">
                                                        <label for="image" class="form-label">Current Image</label><br>
                                                        @if ($banner->image)
                                                            <img src="{{ env('STORAGE_URL') . '/' . $banner->image }}" class="me-2 img-fluid avatar-xl">
                                                        @else
                                                            <span class="small text-danger">No Image</span>
                                                        @endif
                                                    </div>  
                                                    
                                                    <div class="mb-3">
                                                        <label for="status_{{  $banner->id }}" class="form-label">Status: </label></br/>
                                                        <input type="hidden" name="status" value="0">
                                                        <input type="checkbox" name="status" id="status_{{  $banner->id }}" value="1"  {{  $banner->status == 1 ? 'checked' : '' }}  data-switch="success" />
                                                        <label for="status_{{  $banner->id }}" data-on-label="" data-off-label=""></label>
                                                    </div>            
                                                </div>                         
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label"> Type</label>
                                                        <select name="type" class="form-control" id="type{{ $banner->id }}" required>
                                                            <option value="">Select option</option>
                                                            @foreach($types as $key => $label)
                                                                <option value="{{ $key }}" {{ old('type', $banner->type) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('type')
                                                            <p class="small text-danger">{{$message}}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- course dropdown -->
                                                    <div class="mb-3 related-course-field" style="display: none;">
                                                        <label class="form-label">Course</label>
                                                        <select name="courseSelect" class="form-control" id="courseSelect{{ $banner->id }}">
                                                            <option value="">Select option</option>
                                                            @foreach($courses as $course)
                                                                <option value="{{ $course->id }}" 
                                                                    {{ (old('type', $banner->type) === 'course' && old('related_id', $banner->related_id) == $course->id) ? 'selected' : '' }}>
                                                                    {{ $course->name }}
                                                                </option>

                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- link input -->
                                                    <div class="mb-3 related-toolkit-field" style="display: none;">
                                                        <label class="form-label">Link</label>
                                                        <input type="text" class="form-control" name="toolkitInput" id="toolkitInput{{ $banner->id }}" value="{{ old('type', $banner->type) === 'toolkit' ? old('related_id', $banner->related_id) : '' }}">
                                                    </div>

                                                    <!-- hidden actual field -->
                                                    <input type="hidden" name="related_id" id="related_id{{ $banner->id }}">
                                                    @error('related_id')
                                                        <p class="small text-danger">{{$message}}</p>
                                                    @enderror

                                                    <div class="mb-3">
                                                        <label for="example-textarea" class="form-label">Short Description</label>
                                                        <textarea class="form-control" name="short_description"  id="example-textarea" rows="5">{{ $banner->short_description }}</textarea>
                                                        @error('short_description')
                                                            <p class="small text-danger">{{$message}}</p>
                                                        @enderror
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
                        <div id="delete-alert-modal{{ $banner->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-body p-4">
                                        <div class="text-center">
                                            <i class="ri-information-line h1 text-info"></i>
                                            <h4 class="mt-2">Heads up!</h4>
                                            <p class="mt-3">Do you want to delete this banner?</p>
                                            <button type="button" class="btn btn-danger my-2 confirm-delete-banner" data-id="{{ $banner->id }}">
                                                Delete
                                            </button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <script>
        $(document).on('change', '.status-toggle', function () {
        let bannerId = $(this).data('id');
        let status = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: '{{ route("lms.update.banner.status", ":id") }}'.replace(':id', bannerId),
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: status
            },
            success: function (response) {
                console.log(response.message);

                // If the edit modal is open, sync its checkbox
                $('#status_' + bannerId).prop('checked', !!status);
            }
        });
    });

        function bindDeleteBannerEvent() {
            $('.confirm-delete-banner').off('click').on('click', function () {
                let bannerId = $(this).data('id');
                let url = '{{ route("lms.delete.banner", ":id") }}'.replace(':id', bannerId);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'POST'
                    },
                    success: function (response) {
                        $('#delete-alert-modal' + bannerId).modal('hide');

                        // Optional: Reload DataTable or remove the row from the DOM
                        $('#banner-datatable').DataTable().ajax.reload(null, false);

                        toastr.success(response.message);
                    },
                    error: function (xhr) {
                        toastr.error(xhr.responseJSON.message || 'Something went wrong.');
                    }
                });
            });
        }
    </script>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('[id^="type"]').each(function () {

                    const bannerId = $(this).attr('id').replace('type', '');
                    const $typeField = $('#type' + bannerId);
                    const $courseSelect = $('#courseSelect' + bannerId);
                    const $toolkitInput = $('#toolkitInput' + bannerId);
                    const $relatedId = $('#related_id' + bannerId);
                    const $modal = $('#bs-editCourse-modal' + bannerId);

                    function toggleFields() {
                        const selectedType = $typeField.val();

                        $modal.find('.related-course-field').hide();
                        $modal.find('.related-toolkit-field').hide();
                        $relatedId.val('');

                        if (selectedType === 'course') {
                            $modal.find('.related-course-field').show();
                            $relatedId.val($courseSelect.val());
                        } else if (selectedType === 'toolkit') {
                            $modal.find('.related-toolkit-field').show();
                            $relatedId.val($toolkitInput.val());
                        }
                    }

                    $typeField.on('change', toggleFields);

                    $courseSelect.on('change', function () {
                        $relatedId.val($(this).val());
                    });

                    $toolkitInput.on('input', function () {
                        $relatedId.val($(this).val());
                    });
                    toggleFields();
                });
            });
        </script>
    @endpush
    <script>
    $(document).ready(function () {
        $('#banner-datatable').DataTable({
            serverSide: true,
            ajax: "{{ route('banners.ajaxList') }}",
            pageLength: 25,
            columns: [
                { data: 'id', visible: false },
                { data: 'image', orderable: false, searchable: false },
                { data: 'type' },
                { data: 'status', orderable: false, searchable: false },
                { data: 'action', orderable: false, searchable: false }
            ],
            order: [[0, 'desc']],
            responsive: true,
            language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'></i>",
                    next: "<i class='mdi mdi-chevron-right'></i>"
                }
            },
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                bindDeleteBannerEvent();
                
            }
        });
    });
    </script>
    <script>
         $(document).on('change', '.status-toggle', function () {
        let bannerId = $(this).data('id');
        let status = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: '{{ route("lms.update.banner.status", ":id") }}'.replace(':id', bannerId),
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: status
            },
            success: function (response) {
                console.log(response.message);
            }
        });
    });
    </script>
@endsection
