@extends('lms.layout.layout')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Courses</a></li>
                        <li class="breadcrumb-item active">Tool Kits</li>
                    </ol>
                </div>
                <h4 class="page-title">Tool Kits</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('toolkits.create'))
                            <div class="col-sm-5">
                                <a href="{{ route('lms.toolkits.create') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add Tool Kit</a>
                            </div>
                        @endif
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-centered mb-0 w-100 dt-responsive nowrap" id="tool-kits-datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Course</th>
                                    <th>Price</th>
                                    <th>Offer Price</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>

                        @foreach ($tool_kits as $tool_kit)
                        <!-- Edit Modal -->
                        <div class="modal fade" id="edit-tool-kit-modal{{ $tool_kit->id }}" tabindex="-1" role="dialog" aria-labelledby="editToolKitLabel{{ $tool_kit->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="editToolKitLabel{{ $tool_kit->id }}">Edit Tool Kit</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('lms.toolkits.update', $tool_kit->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="name{{ $tool_kit->id }}" class="form-label">Name</label>
                                                        <input type="text" name="name" class="form-control" id="name{{ $tool_kit->id }}" value="{{ $tool_kit->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="course_id{{ $tool_kit->id }}" class="form-label">Course</label>
                                                        <select name="course_id" class="form-control" id="course_id{{ $tool_kit->id }}" required>
                                                            @foreach ($courses as $course)
                                                                <option value="{{ $course->id }}" {{ $tool_kit->course_id == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="price{{ $tool_kit->id }}" class="form-label">Price</label>
                                                        <input type="number" step="0.01" name="price" class="form-control" id="price{{ $tool_kit->id }}" value="{{ $tool_kit->price }}">
                                                    </div>
                                                   
                                                    <div class="mb-3">
                                                        <label for="is_enabled{{ $tool_kit->id }}" class="form-label">Is Enabled</label><br>
                                                        <input type="hidden" name="is_enabled" value="0">
                                                        <input type="checkbox" name="is_enabled" id="is_enabled{{ $tool_kit->id }}" value="1" {{ $tool_kit->is_enabled ? 'checked' : '' }} data-switch="success" />
                                                        <label for="is_enabled{{ $tool_kit->id }}" data-on-label="" data-off-label=""></label>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label" for="media">Upload New Media</label>
                                                        <input type="file" name="media[]" class="form-control" id="media" multiple>
                                                        @error('media.*')
                                                            <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="description{{ $tool_kit->id }}" class="form-label">Description</label>
                                                        <textarea name="description" class="form-control rich-text" id="description{{ $tool_kit->id }}">{{ $tool_kit->description }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="short_description{{ $tool_kit->id }}" class="form-label">Short Description</label>
                                                        <textarea name="short_description" class="form-control rich-text" id="short_description{{ $tool_kit->id }}">{{ $tool_kit->short_description }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="offer_price{{ $tool_kit->id }}" class="form-label">Offer Price</label>
                                                        <input type="number" step="0.01" name="offer_price" class="form-control" id="offer_price{{ $tool_kit->id }}" value="{{ $tool_kit->offer_price }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label d-block mb-2">Current Media</label>
                                                        <div class="d-flex flex-wrap gap-2">
                                                            @forelse($tool_kit->media as $media)
                                                                <div style="position: relative; display:inline-block;">
                                                                    <img src="{{ asset('storage/' . $media->file_path) }}" 
                                                                        alt="Media" width="100" height="100" 
                                                                        style="object-fit: cover; border-radius:8px;">
                                                                </div>
                                                            @empty
                                                                <p class="text-muted">No media uploaded.</p>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Delete Modal -->
                        <div id="delete-tool-kit-modal{{ $tool_kit->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-body p-4">
                                        <div class="text-center">
                                            <i class="ri-information-line h1 text-info"></i>
                                            <h4 class="mt-2">Heads up!</h4>
                                            <p class="mt-3">Do you want to delete this Tool Kit?</p>
                                            <button type="button" class="btn btn-danger my-2 confirm-delete-tool-kit" data-id="{{ $tool_kit->id }}">Delete</button>
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
    <!-- end row -->

    <script>
        function bindDeleteToolKitEvent() {
            $('.confirm-delete-tool-kit').off('click').on('click', function () {
                let $btn = $(this);
                let toolKitId = $btn.data('id');
                let url = '{{ route("lms.toolkits.delete", ":id") }}'.replace(':id', toolKitId);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function (response) {
                        $('#delete-tool-kit-modal' + toolKitId).modal('hide');
                        $('#tool-kits-datatable').DataTable().ajax.reload(null, false);
                    },
                    error: function () {
                        alert('Something went wrong. Could not delete Tool Kit.');
                    }
                });
            });
        }

         $(document).on('change', '.status-toggle', function () {
            let toolkitId = $(this).data('id');
            let status = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: '{{ route("lms.update.toolkit.status", ":id") }}'.replace(':id', toolkitId),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status
                },
                success: function (response) {
                    console.log(response.message);

                    // If the edit modal is open, sync its checkbox
                    $('#is_enabled' + toolkitId).prop('checked', !!status);
                }
            });
        });

        $(document).ready(function () {
            $('#tool-kits-datatable').DataTable({
                serverSide: true,
                ajax: "{{ route('lms.toolkits.ajaxList') }}",
                pageLength: 25,
                columns: [
                    { data: 'name' },
                    { data: 'course_name', searchable: false },
                    { data: 'price' },
                    { data: 'offer_price' },
                    { data: 'is_enabled', orderable: false, searchable: false },
                    { data: 'action', orderable: false, searchable: false },
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
                    bindDeleteToolKitEvent();
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const editorConfiguration = {
                toolbar: [
                    'heading', 
                    '|', 
                    'bold', 
                    'italic', 
                    '|', 
                    'bulletedList', 
                    'numberedList', 
                    '|', 
                    'undo', 
                    'redo'
                ]
            };
            document.querySelectorAll('.rich-text').forEach((textarea) => {
                ClassicEditor
                    .create(textarea, editorConfiguration)
                    .catch(error => {
                        console.error(error);
                    });
            });
        });
    </script>
@endsection