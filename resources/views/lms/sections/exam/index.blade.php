@extends('lms.layout.layout')

@section('list-banners')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Exams</li>
                </ol>
            </div>
            <h4 class="page-title">Exams</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                @if(auth()->user()->hasPermission('exams.create'))
                    <div class="mb-3">
                        <a href="{{ route('lms.exams.create') }}" class="btn btn-danger">
                            <i class="mdi mdi-plus-circle me-2"></i> Add
                        </a>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped dt-responsive nowrap w-100" id="exams-table">
                        <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                                <th>Subject</th>
                                <th>Session Name</th>
                                <th>Duration</th>
                                <th>Total Mark</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                @foreach($exams as $exam)
                <!-- Edit Exam Modal -->
                <div class="modal fade" id="edit-exam-modal{{ $exam->id }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('lms.exams.update', $exam->id) }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Exam</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <!-- Subject -->
                                        <div class="col-md-6 mb-3">
                                            <label>Subject</label>
                                            <select name="subject_id" class="form-control" required>
                                                <option value="">-- Select Subject --</option>
                                                @foreach($subjects as $subject)
                                                    <option value="{{ $subject->id }}" {{ $exam->subject_id == $subject->id ? 'selected' : '' }}>
                                                        {{ $subject->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Exam Name -->
                                        <div class="col-md-6 mb-3">
                                            <label>Exam Name</label>
                                            <input type="text" name="name" class="form-control" value="{{ $exam->name }}" required>
                                        </div>
                                         <!-- Status -->
                                        <div class="col-md-6 mb-3">
                                            <label>Status</label>
                                            <select name="status" class="form-control">
                                                <option value="Scheduled" {{ $exam->status == 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                                                <option value="Ongoing" {{ $exam->status == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                                <option value="Completed" {{ $exam->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                            </select>
                                        </div>
                                        <!-- Batch -->
                                        <div class="col-md-6 mb-3">
                                            <label>Batch</label>
                                            <select name="batch_id" class="form-control" required>
                                                <option value="">-- Select Batch --</option>
                                                @foreach($batches as $batch)
                                                    <option value="{{ $batch->id }}" {{ $exam->batch_id == $batch->id ? 'selected' : '' }}>
                                                        {{ $batch->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- Duration -->
                                        <div class="col-md-6 mb-3">
                                            <label>Duration (Minutes)</label>
                                            <input type="number" name="duration" class="form-control" value="{{ $exam->duration }}" min="1">
                                        </div>
                                        <!-- Total Marks -->
                                        <div class="col-md-6 mb-3">
                                            <label>Total Marks</label>
                                            <input type="number" name="total_marks" class="form-control" value="{{ $exam->total_marks }}" min="1">
                                        </div>
                                        <!-- Minimum Passing Marks -->
                                        <div class="col-md-6 mb-3">
                                            <label>Minimum Passing Marks</label>
                                            <input type="number" name="minimum_passing_marks" class="form-control" value="{{ $exam->minimum_passing_marks }}" min="0">
                                        </div>
                                        <!-- Short Description -->
                                        <div class="col-md-6 mb-3">
                                            <label>Short Description</label>
                                            <input type="text" name="short_description" class="form-control" value="{{ $exam->short_description }}">
                                        </div>

                                        <!-- Description -->
                                        <div class="col-md-12 mb-3">
                                            <label>Description</label>
                                            <textarea name="description" class="form-control" rows="4">{{ $exam->description }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-start">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Delete Exam Modal -->
                <div class="modal fade" id="delete-exam-modal{{ $exam->id }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-body text-center">
                                <i class="ri-information-line h1 text-danger"></i>
                                <h5 class="mt-2">Confirm Delete</h5>
                                <p>Are you sure you want to delete this exam?</p>
                                <button type="button" class="btn btn-danger my-2 confirm-delete-exam" data-id="{{ $exam->id }}">Delete</button>
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

<!-- Scripts -->
<script>
    function bindDeleteExamEvents() {
    $('.confirm-delete-exam').off('click').on('click', function () {
        let id = $(this).data('id');
        let url = '{{ route("lms.exams.delete", ":id") }}'.replace(':id', id);

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'POST'
            },
            success: function () {
                $('#delete-exam-modal' + id).modal('hide');
                $('#exams-table').DataTable().ajax.reload(null, false);
            },
            error: function () {
                alert('Failed to delete exam.');
            }
        });
    });
}

    $(document).ready(function () {
       let table = $('#exams-table').DataTable({
            serverSide: true,
            ajax: "{{ route('lms.exams.ajaxList') }}",
            pageLength: 25,
            columns: [
                { data: 'name', name: 'name' },  
                { data: 'subject', name: 'subject' },
                { data: 'subjectSession', name: 'subjectSession' },
                { data: 'duration', name: 'duration' },
                { data: 'total_marks', name: 'total_marks' },
                { data: 'status', name: 'status' },
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
                bindDeleteExamEvents();
                    $('#exams-table tbody').off('click', 'tr').on('click', 'tr', function (e) {
                    // Prevent click on buttons/links in the last column
                    if (!$(e.target).closest('td').is(':last-child')) {
                        let rowData = table.row(this).data();
                        if (rowData && rowData.id) {
                            window.location.href = "{{ route('lms.exams.questions', ':id') }}"
                                .replace(':id', rowData.id);
                        }
                    }
                });
            }
        });
    });
</script>

@endsection
