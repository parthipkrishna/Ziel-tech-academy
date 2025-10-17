@extends('lms.layout.layout')
@section('list-liveclasses')

    <!-- Pre-loader -->
    <div id="preloader">
        <div id="status">
            <div class="bouncing-loader">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>

    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Manage Live Classes</a></li>
                        <li class="breadcrumb-item active">Live Class</li>
                    </ol>
                </div>
                <h4 class="page-title">Live Class</h4>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('live-classes.create'))
                        <div class="col-sm-5">
                            <a href="{{ route('lms.add.live.class') }}" class="btn btn-danger mb-2">
                                <i class="mdi mdi-plus-circle me-2"></i> Add
                            </a>
                        </div>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-centered mb-0 w-100 dt-responsive nowrap" id="products-datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="display: none;">ID</th>
                                    <th>Thumbnail</th>
                                    <th>Name</th>
                                    <th>Subject</th>
                                    <th>Tutor</th>
                                    <th>Batch</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    {{-- <th>Status</th> --}}
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Live Class Modal -->
    <div class="modal fade" id="editLiveClassModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="editLiveClassForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Live Class</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body row">
                        <input type="hidden" name="id" id="edit_id">

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Meeting Link</label>
                                <input type="text" name="meeting_link" id="edit_meeting_link" class="form-control" required>
                            </div>
                            {{-- Subject Session --}}
                            <div class="col-md-6 mb-3">
                                <label>Subject Session</label>
                                 <select name="subject_session_id" id="session_id" class="form-select" required>
                                    <option value="">Select Session</option>
                                    <!-- Sessions populated via JavaScript -->
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Start Time</label>
                                <input type="datetime-local" name="start_time" id="edit_start_time" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>End Time</label>
                                <input type="datetime-local" name="end_time" id="edit_end_time" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Upload New thumbnail</label>
                                <input type="file" name="thumbnail_image" class="form-control">
                                 <label>Current Image</label>
                                <div id="existing_thumbnail" class="mt-2"></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Subject</label>
                                <select name="subject_id" id="edit_subject_id" class="form-select" required>
                                    <option value="">-- Select Subject --</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}">
                                            {{ strtoupper($subject->name) }} {{ $subject->course ? ' - ' . $subject->course->name : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Super Admin'))
                                <div class="mb-3">
                                    <label>Tutor</label>
                                    <select name="tutor_id" id="edit_tutor_id" class="form-select" required>
                                        <option value="">Select Tutor</option>
                                        @foreach ($tutors as $tutor)
                                            <option value="{{ $tutor->id }}">{{ $tutor->user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            @endif

                            <div class="mb-3">
                                <label>Batch</label>
                                <select name="batch_id" id="edit_batch_id" class="form-select" required>
                                    <option value="">Select Batch</option>
                                    @foreach ($batches as $batch)
                                        <option value="{{ $batch->id }}">{{ $batch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Status</label>
                                <select name="status" id="edit_status" class="form-select" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Ongoing">Ongoing</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Short Summary</label>
                                <textarea name="short_summary" id="edit_short_summary" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="mb-3">
                                <label>Summary</label>
                                <textarea name="summary" id="edit_summary" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="deleteLiveClassModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <form id="deleteLiveClassForm" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <i class="ri-information-line h1 text-danger"></i>
                        <h4 class="mt-2">Confirm Delete</h4>
                        <p>Are you sure you want to delete <strong id="delete_class_name"></strong>?</p>
                        <input type="hidden" name="id" id="delete_id">
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('liveclass-scripts')
        <script>
            $(document).ready(function () {
                if ($.fn.DataTable.isDataTable('#products-datatable')) {
                    $('#products-datatable').DataTable().destroy();
                }

                let table = $('#products-datatable').DataTable({
                    processing: false,
                    serverSide: true,
                    ajax: '{{ route("lms.live.classes.data") }}',
                    language: {
                        paginate: {
                            previous: "<i class='mdi mdi-chevron-left'></i>",
                            next: "<i class='mdi mdi-chevron-right'></i>"
                        },
                        info: "Showing rows _START_ to _END_ of _TOTAL_",
                        lengthMenu: 'Display <select class="form-select form-select-sm ms-1 me-1">' +
                            '<option value="10">10</option>' +
                            '<option value="20">20</option>' +
                            '<option value="-1">All</option>' +
                            '</select> rows'
                    },
                    pageLength: 10,
                    autoWidth: false,
                    responsive: true,
                    order: [[0, 'desc']],
                    drawCallback: function () {
                        $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                        $("#products-datatable_length label").addClass("form-label");
                        document.querySelectorAll(".dataTables_wrapper .row .col-md-6").forEach(function (el) {
                            el.classList.add("col-sm-6");
                            el.classList.remove("col-sm-12", "col-md-6");
                        });
                    },
                    pageLength: 25,
                    columns: [
                        { data: 'id', name: 'id', visible: false },
                        {
                            data: 'thumbnail_image',
                            name: 'thumbnail_image',
                            render: data => data
                                ? `<img src="{{ env('STORAGE_URL') }}/${data}" 
                                        width="70" height="50" 
                                        class="me-2" 
                                        style="object-fit: cover;">`
                                : '<span class="small text-danger">No Image</span>'
                        },
                        { data: 'name', name: 'name' },
                        { data: 'subject.name', name: 'subject.name' },
                        { data: 'tutor.user.name', name: 'tutor.user.name' },
                        { data: 'batch.name', name: 'batch.name' },
                        { data: 'start_time', name: 'start_time' },
                        { data: 'end_time', name: 'end_time' },
                       
                        { data: 'actions', name: 'actions', orderable: false, searchable: false }
                    ]
                });

                // Open Edit Modal
                $('#products-datatable').on('click', '.editLiveClass', function () {
                    const data = $(this).data();

                    $('#edit_id').val(data.id);
                    $('#edit_name').val(data.name);
                    $('#edit_meeting_link').val(data.meeting_link);
                    $('#edit_start_time').val(new Date(data.start_time).toISOString().slice(0, 16));
                    $('#edit_end_time').val(new Date(data.end_time).toISOString().slice(0, 16));
                    $('#edit_subject_id').val(data.subject_id);
                    $('#edit_tutor_id').val(data.tutor_id);
                    $('#edit_batch_id').val(data.batch_id);
                    $('#edit_short_summary').val(data.short_summary);
                    $('#edit_summary').val(data.summary);
                    $('#edit_subject_session_id').val(data.subject_session_id);
                    $('#edit_status').val(data.status);
                    const sessionDropdown = $('#session_id');
                        sessionDropdown.html('<option value="">Loading...</option>');
                        $.ajax({
                            url: "{{ route('get-sessions', ['subject' => ':subjectId']) }}".replace(':subjectId', data.subject_id),
                            type: 'GET',
                            success: function(sessions) {
                                sessionDropdown.html('<option value="">Select Session</option>');
                                $.each(sessions, function(key, session) {
                                    sessionDropdown.append(
                                        `<option value="${session.id}" ${session.id == data.subject_session_id ? 'selected' : ''}>
                                            ${session.title}
                                        </option>`
                                    );
                                });
                            }
                        });

                    if (data.thumbnail_image) {
                        $('#existing_thumbnail').html(
                            `<img src="{{ env('STORAGE_URL') }}/` + data.thumbnail_image + `"  style="width: 200px; height: 200px; object-fit: cover;">`
                        );
                    } else {
                        $('#existing_thumbnail').html('<span class="text-muted">No image</span>');
                    }
                    const updateUrl = "{{ url('/cms/mobile/live-classes/update') }}";
                    $('#editLiveClassForm').attr('action', `${updateUrl}/${data.id}`);
                    $('#editLiveClassModal').modal('show');
                });

                // Open Delete Modal
                $('#products-datatable').on('click', '.deleteLiveClass', function () {
                    const id = $(this).data('id');
                    const name = $(this).data('name');

                    $('#delete_id').val(id);
                    $('#delete_class_name').text(name);

                    // Correctly set the form's action URL
                    const deleteUrl = `/cms/mobile/live-classes/delete/${id}`;
                    $('#deleteLiveClassForm').attr('action', deleteUrl);

                    $('#deleteLiveClassModal').modal('show');
                });

            });
        </script>
    @endpush
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