@extends('lms.layout.layout')
@section('list-banners')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Subject sessions</a></li>
                        <li class="breadcrumb-item active">sessions</li>
                    </ol>
                </div>
                <h4 class="page-title">Subject Sessions</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('subject-sessions.create'))
                            <div class="col-sm-5">
                                <a href="{{ route('subject-sessions.create') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                            </div>
                        @endif
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-centered mb-0 w-100 dt-responsive nowrap" id="subject-session-datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="display:none;">ID</th>
                                    <th>Title</th>
                                    <th>Subject</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>

                        @foreach ($subjectSessions as $session)
                            <!-- Edit Modal -->
                            <div class="modal fade" id="editSessionModal{{ $session->id }}" tabindex="-1" role="dialog" aria-labelledby="editSessionLabel{{ $session->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <form action="{{ route('subject-sessions.update', $session->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="editSessionLabel{{ $session->id }}">Edit Subject Session</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body row">
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label">Title</label>
                                                    <input type="text" name="title" class="form-control" value="{{ $session->title }}" required>
                                                </div>
                                                {{-- Subject--}}
                                               <div class="col-md-6 mb-3">
                                                    <label class="form-label">Subject</label>
                                                    <select name="subject_id" class="form-control" required>
                                                        <option value="">-- Select Subject --</option>
                                                        @foreach($subjects as $subject)
                                                            <option value="{{ $subject->id }}" {{ $session->subject_id == $subject->id ? 'selected' : '' }}>
                                                                {{ strtoupper($subject->name) }} {{ $subject->course ? ' - ' . $subject->course->name : '' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-12">
                                                    <label class="form-label">Description</label>
                                                    <textarea name="description" class="form-control" rows="5">{{ $session->description }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div id="deleteSessionModal{{ $session->id }}" class="modal fade" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-body p-4 text-center">
                                            <i class="ri-information-line h1 text-info"></i>
                                            <h4 class="mt-2">Heads up!</h4>
                                            <p class="mt-3">Do you want to delete this subject session?</p>
                                            <button type="button" class="btn btn-danger my-2 confirm-delete-subject-session" data-id="{{ $session->id }}"> Delete </button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
        function bindDeleteSubjectSessionEvent() {
            $('.confirm-delete-subject-session').off('click').on('click', function () {
                let sessionId = $(this).data('id');
                let url = '{{ route("subject-sessions.delete", ":id") }}'.replace(':id', sessionId);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'POST'
                    },
                    success: function (response) {
                        $('#deleteSessionModal' + sessionId).modal('hide');
                        $('#subject-session-datatable').DataTable().ajax.reload(null, false);
                    },
                    error: function () {
                                alert('Something went wrong. Could not delete FAQ.');
                            }
                });
            });
        }
</script>
<script>
    $(document).ready(function () {
        $('#subject-session-datatable').DataTable({
            serverSide: true,
            ajax: "{{ route('subject-sessions.ajaxList') }}",
            pageLength: 25,
            columns: [
                { data: 'id', visible: false },
                { data: 'title' },
                { data: 'subject', name: 'subject' },
                { data: 'description' },
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
                bindDeleteSubjectSessionEvent();
            }
        });
    });
</script>

@endsection
