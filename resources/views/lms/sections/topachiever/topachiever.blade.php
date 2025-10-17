@extends('lms.layout.layout')
@section('list-top-achievers')

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
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Mobile & App Content Management</a></li>
                    <li class="breadcrumb-item active">Top Achiever</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    @if(auth()->user()->hasPermission('top-achievers.create'))
                        <div class="col-sm-5">
                            <a href="{{ route('lms.add.top.achiever') }}" class="btn btn-danger mb-2">
                                <i class="mdi mdi-plus-circle me-2"></i> Add
                            </a>
                        </div>
                    @endif
                    <div class="col-sm-7"></div><!-- end col-->
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-centered mb-0 w-100 dt-responsive nowrap" id="top-achievers-datatable">
                        <thead class="table-dark">
                            <tr>
                                <th style="display:none;">ID</th>
                                <th></th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <!-- Edit Modal -->
                     @foreach ($top_achievers as $achiever )
                    <div class="modal fade" id="bs-TopAchiever-modal{{ $achiever->id}}" tabindex="-1" role="dialog" aria-labelledby="TopAchieverLabel{{ $achiever->id}}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="TopAchieverLabel{{ $achiever->id}}">Edit Top Achiever</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('lms.update.top.achiever', $achiever->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf  
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="student_id_{{ $achiever->id }}" class="form-label">Student</label>
                                                    <select class="form-control student-select"
                                                        name="student_id"
                                                        id="student_id_{{ $achiever->id }}"
                                                        style="width: 100%"
                                                        data-achiever-id="{{ $achiever->id }}"
                                                        data-selected-id="{{ $achiever->student_id }}"
                                                        data-selected-text="{{ $achiever->name }} "
                                                        data-selected-course="{{ $achiever->course_id }}"
                                                        required>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="name_display_{{ $achiever->id }}">Name</label>
                                                    <input type="text"
                                                        id="name_display_{{ $achiever->id }}"
                                                        class="form-control"
                                                        placeholder="Name"
                                                        value="{{ $achiever->name }}"
                                                        disabled>
                                                    <input type="hidden" name="name" id="name_{{ $achiever->id }}" value="{{ $achiever->name }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="status_{{  $achiever->id}}" class="form-label">Status: </label></br/>
                                                    <input type="hidden" name="status" value="0">
                                                    <input type="checkbox" name="status" id="status_{{  $achiever->id}}" value="1"  {{  $achiever->status == 1 ? 'checked' : '' }}  data-switch="success" />
                                                    <label for="status_{{  $achiever->id}}" data-on-label="" data-off-label=""></label>
                                                </div>

                                            </div>
                                            <div class="col-lg-6">

                                                <div class="mb-3">
                                                    <label for="image" class="form-label">Upload Image</label>
                                                    <input type="file" name="image" class="form-control">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="image" class="form-label">Current Profile Image</label><br>
                                                    @if ($achiever->image)
                                                        <img src="{{ env('STORAGE_URL') . '/' . $achiever->image }}" class="me-2 img-fluid avatar-xl" alt="Current Image">
                                                    @else
                                                        <span class="small text-danger">No Image</span>
                                                    @endif
                                                </div>
                                                <div class="mb-3">
                                                    <label for="course_id_{{ $achiever->id }}" class="form-label">Course</label>
                                                    <select class="form-select course-select" 
                                                        name="course_id" 
                                                        id="course_id_{{ $achiever->id }}" 
                                                        required>
                                                        <option value="">Select option</option>
                                                        <!-- Dynamic options will be added here -->
                                                    </select>
                                                </div>
                                            </div>
                                        </div>                                                                                                              
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    <!-- Delete Alert Modal -->
                    <div id="delete-alert-modal{{ $achiever->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-body p-4">
                                    <div class="text-center">
                                        <i class="ri-information-line h1 text-info"></i>
                                        <h4 class="mt-2">Heads up!</h4>
                                        <p class="mt-3">Do you want to delete this Top Achiever?</p>
                                        <button type="button" class="btn btn-danger my-2 confirm-delete-achiever" data-id="{{ $achiever->id }}">Delete</button>
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

@push('scripts')
<script>
    $(document).ready(function () {
        function initStudentSelect(selectElement) {
            const achieverId = selectElement.data('achiever-id');
            const selectedId = selectElement.data('selected-id');
            const selectedText = selectElement.data('selected-text');
            const selectedCourse = selectElement.data('selected-course');
            const modal = selectElement.closest('.modal');

            selectElement.select2({
                theme: 'bootstrap-5',
                dropdownParent: modal,
                ajax: {
                    url: "{{ route('students.search') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { 
                            search_term: params.term,
                            with_courses: true // Request courses with student data
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function (student) {
                                return {
                                    id: student.id,
                                    text: student.text,
                                    full_name: student.student_name,
                                    courses: student.courses || {} // Ensure courses exists
                                };
                            })
                        };
                    },
                    cache: true,
                },
            });

            // Preload the selected option
            if (selectedId && selectedText) {
                const option = new Option(selectedText, selectedId, true, true);
                selectElement.append(option).trigger('change');
                
                // Fetch courses for the pre-selected student
                $.ajax({
                    url: "{{ route('students.search') }}",
                    data: { id: selectedId, with_courses: true },
                    dataType: 'json',
                    success: function(data) {
                        if (data.length > 0) {
                            const student = data[0];
                            updateCourseDropdown(modal, student.courses, selectedCourse);
                        }
                    }
                });
            }

            // When a student is selected, update the name and courses
            selectElement.on('select2:select', function (e) {
                const selectedData = e.params.data;
                modal.find(`#name_display_${achieverId}`).val(selectedData.full_name);
                modal.find(`#name_${achieverId}`).val(selectedData.full_name);
                updateCourseDropdown(modal, selectedData.courses);
            });
        }

        // Function to update course dropdown
        function updateCourseDropdown(modal, courses, selectedCourseId = null) {
            const courseSelect = modal.find('.course-select');
            courseSelect.empty().append('<option value="">Select option</option>');
            
            if (courses && Object.keys(courses).length > 0) {
                $.each(courses, function(id, name) {
                    const option = new Option(name, id, false, false);
                    courseSelect.append(option);
                });
                
                if (selectedCourseId && courses[selectedCourseId]) {
                    courseSelect.val(selectedCourseId).trigger('change');
                }
            } else {
                courseSelect.append('<option value="" disabled>No subscribed courses found</option>');
            }
        }

        // Initialize select2 on modal show
        $('.modal').on('shown.bs.modal', function () {
            $(this).find('.student-select').each(function () {
                if (!$(this).hasClass('select2-hidden-accessible')) {
                    initStudentSelect($(this));
                }
            });
        });
    });
</script>
<script>
    function bindDeleteAchieverEvent() {
        $('.confirm-delete-achiever').off('click').on('click', function () {
            let achieverId = $(this).data('id');
            let url = '{{ route("lms.delete.top.achiever", ":id") }}'.replace(':id', achieverId);

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'POST'
                },
                success: function (response) {
                    $('#delete-alert-modal' + achieverId).modal('hide');
                    $('#top-achievers-datatable').DataTable().ajax.reload(null, false);
                },
                error: function () {
                    alert('Something went wrong. Could not delete FAQ.');
                }
            });
        });
    }
$(document).ready(function () {
    $('#top-achievers-datatable').DataTable({
        serverSide: true,
        responsive: true,
        ajax: '{{ route('top-achievers.ajax') }}',
        pageLength: 25,
        columns: [
            { data: 'id', visible: false },
            { data: 'image', name: 'image' },
            { data: 'name', name: 'name' },
            { data: 'course', name: 'course' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
       language: {
                paginate: {
                    previous: "<i class='mdi mdi-chevron-left'></i>",
                    next: "<i class='mdi mdi-chevron-right'></i>"
                },
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                lengthMenu: 'Display <select class="form-select form-select-sm ms-1 me-1">' +
                    '<option value="5">5</option>' +
                    '<option value="10">10</option>' +
                    '<option value="20">20</option>' +
                    '<option value="-1">All</option>' +
                    '</select> entries'
            },
            pageLength: 10,
            order: [[0, "desc"]],
            drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                bindDeleteAchieverEvent(); 
            },
    });
});
</script>
<script>
        $(document).on('change', '.status-toggle', function () {
        let achieverId = $(this).data('id');
        let status = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: '{{ route("lms.update.achiever.status", ":id") }}'.replace(':id', achieverId),
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: status
            },
            success: function (response) {
                console.log(response.message);
                $('#status_' + achieverId).prop('checked', !!status);
            }
        });
    });
</script>

@endpush

@endsection
