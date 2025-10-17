@extends('lms.layout.layout')
@section('list-banners')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                    <li class="breadcrumb-item active">Videos</li>
                </ol>
            </div>
            <h4 class="page-title">Video Management</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    @if(auth()->user()->hasPermission('videos.create'))
                        <div class="col-sm-5">
                            <a href="{{ route('lms.videos.create') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                        </div>
                    @endif
                    <div class="col-sm-7"></div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-centered mb-0 w-100 dt-responsive nowrap" id="videos-datatable">
                        <thead class="table-dark">
                            <tr>
                                <th style="display:none;">ID</th>
                                <th>Thumbnail</th>
                                <th>Title</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>

                    @foreach ($videos as $video)
                        <!-- Delete Modal -->
                        <div id="deleteVideoModal{{ $video->id }}" class="modal fade" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-body p-4 text-center">
                                        <i class="ri-information-line h1 text-info"></i>
                                        <h4 class="mt-2">Heads up!</h4>
                                        <p class="mt-3">Do you want to delete this video?</p>
                                        <button type="button" class="btn btn-danger my-2 confirm-delete-video" data-id="{{ $video->id }}"> Delete </button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).on('change', '.status-toggle', function () {
        let batchId = $(this).data('id');
        let status = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: '{{ route("lms.update.video.status", ":id") }}'.replace(':id', batchId),
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
    function bindDeleteVideoEvent() {
        $('.confirm-delete-video').off('click').on('click', function () {
            let videoId = $(this).data('id');
            let url = '{{ route("lms.videos.delete", ":id") }}'.replace(':id', videoId);

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'POST'
                },
                success: function (response) {
                    $('#deleteVideoModal' + videoId).modal('hide');
                    $('#videos-datatable').DataTable().ajax.reload(null, false);
                },
                error: function () {
                    alert('Something went wrong. Could not delete video.');
                }
            });
        });
    }
</script>

<script>
    $(document).ready(function () {
        $('#videos-datatable').DataTable({
            serverSide: true,
            ajax: "{{ route('lms.videos.ajaxList') }}",
            pageLength: 25,
            columns: [
                { data: 'id', visible: false },
                { data: 'thumbnail', orderable: false, searchable: false },
                { data: 'title' },
                { data: 'duration' },
                { data: 'status' },
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
                bindDeleteVideoEvent();
            }
        });
    });
</script>

@endsection