@extends('lms.layout.layout')
@section('list-student-enrollment')
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
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Student-Enrollment</a></li>
                        <li class="breadcrumb-item active">Enrollment</li>
                    </ol>
                </div>
                <h4 class="page-title">Enrollment</h4>
            </div>
        </div>
      </div>
    <!-- end page title -->
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('enrollments.create'))
                        <div class="col-sm-5">
                            <a href="{{ route('lms.add.student.enroll') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                        </div>
                        @endif
                        <div class="col-sm-7">
                            <div class="text-sm-end">
                                {{-- <button type="button" class="btn btn-success mb-2 me-1"><i class="mdi mdi-cog"></i></button> --}}
                                <button type="button" class="btn btn-light mb-2 me-1">
                                    <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-importCertificate-modal">
                                        <i class="mdi mdi-square-edit-outline"></i>
                                        Import
                                    </a>
                                </button>              
                                <a href="{{ route('lms.export.student') }}" class="btn btn-light mb-2 me-1">
                                    <i class="mdi mdi-square-edit-outline"></i> Export
                                </a>
                                {{-- <button type="button" class="btn btn-light mb-2">Export</button> --}}
                            </div>
                        </div><!-- end col-->
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-primary" id="filter-btn">Filter</button>
                            <button type="reset" class="btn btn-secondary ms-2" id="reset-btn">Reset</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-centered mb-0 w-100 dt-responsive nowrap" id="enrollments-datatable">
                            <thead class="table-dark">
                                <tr>
                                    <th style="display:none;">ID</th>
                                    <th>Student</th>
                                    <th>Name</th>
                                    <th>Course</th>
                                    <th>Email</th>
                                    <th>Contact Number</th>
                                    <th>Enrolled Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        @foreach ($enrollment_main as $student ) 
                        <div class="modal fade" id="bs-editOnlineEnroll-modal{{ $student['id'] }}" tabindex="-1" role="dialog" aria-labelledby="editOnlineEnrollLabel{{ $student['id'] }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="editOnlineEnrollLabel{{ $student['id'] }}">Edit Enroll</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('lms.update.student.enroll', $student['id']) }}" method="POST" enctype="multipart/form-data">
                                            @csrf   
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom01">Name</label>
                                                        <input type="text" name="first_name" value="{{ $student['first_name'] }}" class="form-control" id="name" placeholder="Name" disabled>
                                                    </div>
            
                                                </div>
                                                <div class="col-lg-6"> 
                                                    <div class="mb-3">
                                                        <label for="platform" class="form-label">Status</label>
                                                        <select name="status" class="form-control">
                                                            <option value="">{{  $student['status'] }}</option>
                                                            <option value="">Choose a Status</option>
                                                            @foreach(\App\Models\StudentEnrollment::getStatusOptions() as $status)
                                                                <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>
                                                                    {{ ucfirst($status) }}
                                                                </option>
                                                            @endforeach
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
                        <div id="delete-alert-modal{{ $student['id'] }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-body p-4">
                                        <div class="text-center">
                                            <i class="ri-information-line h1 text-info"></i>
                                            <h4 class="mt-2">Heads up!</h4>
                                            <p class="mt-3">Do you want to delete this Student Enroll?</p>

                                            <button type="button"
                                                    class="btn btn-danger my-2 confirm-delete-student-enroll"
                                                    data-id="{{ $student['id'] }}">
                                                Delete
                                            </button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <div class="modal fade" id="bs-importCertificate-modal" tabindex="-1" role="dialog" aria-labelledby="importMarkListLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="importMarkListLabel"><I>Import</I></h4>
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
                                        <div class="mb-3">
                                            <p>
                                                Download the 
                                                <a href="{{ asset('lms/excel/student_demo_data_fixed.xlsx') }}" download>
                                                    Sample Excel File
                                                </a> 
                                                for Student Enrollment.
                                            </p>
                                            <p class="text-danger">
                                                ⚠️ Please ensure that <strong>Course Name</strong> and <strong>Batch Name</strong> in the Excel file 
                                                exactly match the names in the system tables.  
                                                If they don’t match, the system will show an alert and the data will not be imported.
                                            </p>
                                        </div>
                                        <form action="{{ route('lms.import.student') }}" method="POST" enctype="multipart/form-data" id="studentImportForm">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">Upload Excel File</label>
                                                <input type="file" name="file" class="form-control" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Import</button>
                                        </form>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    <!-- end row -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const filterBtn = document.getElementById('filter-btn');
        const resetBtn = document.getElementById('reset-btn');
        const table = document.getElementById('products-datatable');
        const rows = table.querySelectorAll('tbody tr');

        // Helper function to format date as YYYY-MM-DD
        const formatDate = (date) => date.toISOString().split('T')[0];

        // Set default values: today as start date and +30 days for end date
        const today = new Date();
        const defaultEndDate = new Date();
        defaultEndDate.setDate(today.getDate() + 30);

        startDateInput.value = formatDate(today);
        endDateInput.value = formatDate(defaultEndDate);

        // Auto-set end date when start date changes
        startDateInput.addEventListener('change', function() {
            if (this.value) {
                const startDate = new Date(this.value);
                const endDate = new Date(startDate);
                endDate.setDate(startDate.getDate() + 30);
                endDateInput.value = formatDate(endDate);
            }
        });

        // Manual filter button
        filterBtn.addEventListener('click', filterTable);

        // Reset button (reset to default dates but DO NOT filter automatically)
        resetBtn.addEventListener('click', function() {
            startDateInput.value = formatDate(today);
            endDateInput.value = formatDate(defaultEndDate);
            rows.forEach(row => row.style.display = '');
        });

        function filterTable() {
            const startDate = startDateInput.value ? new Date(startDateInput.value) : null;
            const endDate = endDateInput.value ? new Date(endDateInput.value) : null;
            
            if (!startDate && !endDate) {
                rows.forEach(row => row.style.display = '');
                return;
            }

            rows.forEach(row => {
                const dateCell = row.cells[5].textContent;
                const rowDate = new Date(dateCell);
                let shouldShow = true;
                
                if (startDate && rowDate < startDate) {
                    shouldShow = false;
                }
                
                if (endDate && rowDate > endDate) {
                    shouldShow = false;
                }
                
                row.style.display = shouldShow ? '' : 'none';
            });
        }
    });
    </script>
    <script>
       function bindDeleteStudentEnrollEvent() {
            $('.confirm-delete-student-enroll').off('click').on('click', function () {
                let enrollId = $(this).data('id');
                let url = '{{ route("lms.delete.student.enroll", ":id") }}'.replace(':id', enrollId);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'POST'
                    },
                    success: function (response) {
                        $('#delete-alert-modal' + enrollId).modal('hide');
                        // Refresh the DataTable without reloading the whole page
                        $('#enrollments-datatable').DataTable().ajax.reload(null, false);
                    
                    },
                    error: function (xhr) {
                        alert(xhr.responseJSON?.message || 'Something went wrong. Could not delete enrollment.');
                    }
                });
            });
        }
    </script>

<script>
$(document).ready(function () {
    $('#enrollments-datatable').DataTable({
        serverSide: true,
        responsive: true,
        ajax: {
            url: '{{ route('enrollments.list.ajax') }}',
            data: function (d) {
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
                return d; // Add this line
            },
            dataSrc: 'data' // Add this if your server response wraps data in a 'data' property
        },
        pageLength: 25,
        columns: [
            { data: 'id', name: 'id', visible: false },
            { data: 'student', name: 'student', orderable: false, searchable: false },
            { data: 'first_name', name: 'student.first_name' },
            { data: 'course_name', name: 'course.name' },
            { data: 'email', name: 'student.user.email' },
            { data: 'phone', name: 'student.user.phone' },
            { data: 'created_at', name: 'created_at' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'></i>",
                next: "<i class='mdi mdi-chevron-right'></i>"
            }
        },
        drawCallback: function () {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                bindDeleteStudentEnrollEvent();
            }
    });
});
</script>
<script>
    $(document).ready(function () {
    $('#studentImportForm').submit(function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        $('#modal-error-list').html('');
        $('#modal-success-message').html('');

        $.ajax({
            type: 'POST',
            url: "{{ route('lms.import.student') }}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#modal-success-message').text(response.message ?? 'Students imported successfully!');
                let successModal = new bootstrap.Modal(document.getElementById('success-alert-modal'));
                successModal.show();
                $('#studentImportForm')[0].reset(); 
                setTimeout(() => {
                                    window.location.href = "{{ route('lms.students.enroll') }}";
                                }, 1500);
                            
            },
            error: function (xhr) {
                let errorMessage = xhr.responseJSON?.message || 'Something went wrong during import.';
                $('#modal-error-list').html('<p>' + errorMessage + '</p>');
                let errorModal = new bootstrap.Modal(document.getElementById('danger-alert-modal'));
                errorModal.show();
            }
        });
    });
});

</script>



@endsection
