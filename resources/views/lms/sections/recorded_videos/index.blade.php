@extends('lms.layout.layout')

@section('list-banners')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Recorded Videos</li>
                </ol>
            </div>
            <h4 class="page-title">Recorded Videos</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if(auth()->user()->hasPermission('recorded-videos.create'))
                    <div class="mb-3">
                        <a href="{{ route('lms.recorded.videos.create') }}" class="btn btn-danger">
                            <i class="mdi mdi-plus-circle me-2"></i> Add 
                        </a>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped dt-responsive nowrap w-100" id="recorded-videos-table">
                        <thead class="table-dark">
                            <tr>
                                <th>Subject</th>
                                <th>Session</th>
                                <th>Video</th>
                                <th>Duration</th>
                                <th>Order</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                @foreach($recordedVideos as $video)
                    <!-- Edit Modal -->
                    <div class="modal fade" id="edit-video-modal{{ $video->id }}" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form method="POST" action="{{ route('lms.recorded.videos.update', $video->id) }}">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Recorded Video</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            {{-- Subject --}}
                                            <div class="col-md-6 mb-3">
                                                <label>Subject</label>
                                                <select name="subject_id" class="form-control" required>
                                                    <option value="">-- Select Subject --</option>
                                                    @foreach($subjects as $subject)
                                                        <option value="{{ $subject->id }}" {{ $video->subject_id == $subject->id ? 'selected' : '' }}>
                                                                {{ strtoupper($subject->name) }} {{ $subject->course ? ' - ' . $subject->course->name : '' }}
                                                            </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                           {{-- Subject Session --}}
                                            <div class="col-md-6 mb-3">
                                                <label>Subject Session</label>
                                                <select name="subject_session_id" id="session_id" class="form-select" required>
                                                    <option value="">-- Select Session --</option>
                                                    @foreach($subjects as $subject)
                                                        @if($subject->id == $video->subject_id)
                                                            @forelse($subject->sessions as $session)
                                                                <option value="{{ $session->id }}" {{ $video->subject_session_id == $session->id ? 'selected' : '' }}>
                                                                    {{ $session->title }}
                                                                </option>
                                                            @empty
                                                                <option disabled>No sessions available</option>
                                                            @endforelse
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- Video --}}
                                            <div class="col-md-6 mb-3">
                                                <label>Video</label>
                                                <select name="video_id" class="form-control" required>
                                                    <option value="">-- Select Video --</option>
                                                    @foreach($videos as $v)
                                                        <option value="{{ $v->id }}" {{ $video->video_id == $v->id ? 'selected' : '' }}>
                                                            {{ $v->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Video Order --}}
                                            <div class="col-md-6 mb-3">
                                                <label>Video Order</label>
                                                <input type="number" name="video_order" class="form-control" value="{{ $video->video_order }}" required>
                                            </div>

                                            {{-- Status --}}
                                            <div class="col-md-6 mb-3">
                                                <label>Status</label><br>
                                                <input type="hidden" name="is_enabled" value="0">
                                                <input type="checkbox" name="is_enabled" id="switch-is_enable{{ $video->id }}" value="1" {{ $video->is_enabled ? 'checked' : '' }} data-switch="success" />
                                                <label for="switch-is_enable{{ $video->id }}" data-on-label="Yes" data-off-label="No"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-start"">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <!-- Delete Modal -->
                    <div class="modal fade" id="delete-video-modal{{ $video->id }}" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-body text-center">
                                    <i class="ri-information-line h1 text-danger"></i>
                                    <h5 class="mt-2">Confirm Delete</h5>
                                    <p>Are you sure you want to delete this video?</p>
                                    <button type="button" class="btn btn-danger my-2 confirm-delete-recorded" data-id="{{ $video->id }}">Delete</button>
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

<script>
    function bindDeleteRecordedEvents() {
        $('.confirm-delete-recorded').off('click').on('click', function () {
            let id = $(this).data('id');
            let url = '{{ route("lms.recorded.videos.delete", ":id") }}'.replace(':id', id);

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'POST'
                },
                success: function () {
                    $('#delete-video-modal' + id).modal('hide');
                    $('#recorded-videos-table').DataTable().ajax.reload(null, false);
                },
                error: function () {
                    alert('Failed to delete recorded video.');
                }
            });
        });
    }

    $(document).ready(function () {
    $('#recorded-videos-table').DataTable({
        serverSide: true,
        ajax: "{{ route('lms.recorded.videos.ajaxList') }}",
        pageLength: 25,
        columns: [
            { data: 'subject', name: 'subject' },         // Matches ->addColumn('subject', ...)
            { data: 'session', name: 'session' },         // Matches ->addColumn('session', ...)
            { data: 'title', name: 'title' },             // Matches ->addColumn('title', ...)
            { data: 'duration', name: 'duration' },
            { data: 'video_order', name: 'video_order' }, // Comes from the model directly
            { data: 'is_enabled', name: 'is_enabled' },   // Matches ->addColumn('is_enabled', ...)
            { data: 'action', name: 'action', orderable: false, searchable: false },
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
            bindDeleteRecordedEvents(); 
        }
    });
});

</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Handle subject change
        $('select[name="subject_id"]').change(function() {
            const subjectId = $(this).val();
            const sessionDropdown = $('#session_id');
            
            // Reset session dropdown
            sessionDropdown.html('<option value="">Select Session</option>');
            
            if (!subjectId) return; // Exit if no subject selected
            
            // Fetch sessions via AJAX
            $.ajax({
                url: "{{ route('get-sessions', ['subject' => ':subjectId']) }}".replace(':subjectId', subjectId),
                type: 'GET',
                success: function(sessions) {
                    if (sessions.length > 0) {
                        $.each(sessions, function(key, session) {
                            sessionDropdown.append(
                                `<option value="${session.id}">${session.title}</option>`
                            );
                        });
                    } else {
                        sessionDropdown.append(
                            `<option value="">No sessions available</option>`
                        );
                    }
                },
                error: function() {
                    alert('Error loading sessions');
                }
            });
        });

        // Preselect values if form has old input (after validation error)
        @if(old('subject_id'))
            $('select[name="subject_id"]').val("{{ old('subject_id') }}").trigger('change');
            setTimeout(() => {
                $('#session_id').val("{{ old('session_id') }}");
            }, 500);
        @endif
    });
    </script>

@endsection
