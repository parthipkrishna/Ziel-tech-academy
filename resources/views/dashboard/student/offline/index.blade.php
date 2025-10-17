@extends('layouts.dashboard')
@section('list-offline-student-enrollment')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Offline-Student-Enrollment</a></li>
                        <li class="breadcrumb-item active"> Enrollment</li>
                    </ol>
                </div>
                <h4 class="page-title"> Enrollment</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                            @if(auth()->user()->hasPermission('offline-students', 'create'))
                                <div class="col-sm-5">
                                    <a href="{{ route('admin.offline.student.enroll.create') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                                <div class="col-sm-7">
                            @endif
                        </div><!-- end col-->
                    </div>
                    <div class="col-sm-7">
                            <div class="text-sm-end">
                                {{-- <button type="button" class="btn btn-success mb-2 me-1"><i class="mdi mdi-cog"></i></button> --}}
                                <button type="button" class="btn btn-light mb-2 me-1">
                                    <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-importCertificate-modal">
                                        <i class="mdi mdi-square-edit-outline"></i>
                                        Export
                                    </a>
                                </button>
                                </div>
                            </div>                 
                    <div class="table-responsive">
                        <table class="table table-centered table-borderless table-hover w-100 dt-responsive nowrap" id="products-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>Student</th>
                                    <th>Name</th>
                                    <th>Course</th>
                                    <th>Email</th>
                                    <th>Contact Number</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (array_reverse($enrollment_main) as $student)
                                    <tr>
                                        <td class="table-user">
                                            @if ($student['profile_image'])
                                                <img src="{{ env('STORAGE_URL') . '/' . $student['profile_image']  }}" class="me-2 rounded-circle">
                                            @else
                                                <span class="small text-danger">No Image</span>
                                            @endif
                                        </td>
                                        <td>{{ $student['first_name'] }}</td>
                                        <td>{{ $student['course_name'] }}</td>
                                        <td>{{ $student['email'] }}</td>
                                        <td>{{ $student['phone'] }}</td>
                                        <td>
                                            @if($student['status'] == 'active')
                                                <button type="button" class="btn btn-soft-success rounded-pill">Active</button>
                                            @elseif($student['status'] == 'completed')
                                                <button type="button" class="btn btn-soft-primary rounded-pill">Completed</button>
                                            @elseif($student['status'] == 'cancelled')
                                                <button type="button" class="btn btn-soft-danger rounded-pill">Cancelled</button>
                                            @elseif($student['status'] == 'enrolled')
                                                <button type="button" class="btn btn-soft-warning rounded-pill">Enrolled</button>
                                            @endif
                                        </td>

                                        <td>
                                            @if(auth()->user()->hasPermission('offline-students', 'edit'))
                                                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-editOnlineEnroll-modal{{ $student['id'] }}">
                                                    <i class="mdi mdi-square-edit-outline"></i>
                                                </a>
                                            @endif
                                            @if(auth()->user()->hasPermission('offline-students', 'delete'))
                                                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal{{ $student['id'] }}">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Edit Modal-->
                                    <div class="modal fade" id="bs-editOnlineEnroll-modal{{ $student['id'] }}" tabindex="-1" role="dialog" aria-labelledby="editOnlineEnrollLabel{{ $student['id'] }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="editOnlineEnrollLabel{{ $student['id'] }}">Edit Enroll</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.offline.student.enroll.update', $student['id']) }}" method="POST" enctype="multipart/form-data">
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
                                                                        <option value="">{{  ucfirst($student['status']) }}</option>
                                                                        <option value="">Choose a Status</option>
                                                                        @foreach(\App\Models\OfflineCourseEnrollment::getStatusOptions() as $status)
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
                                    <!-- Delete Alert Modal  -->
                                    <div id="delete-alert-modal{{ $student['id'] }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-body p-4">
                                                    <div class="text-center">
                                                        <i class="ri-information-line h1 text-info"></i>
                                                        <h4 class="mt-2">Heads up!</h4>
                                                        <p class="mt-3">Do you want to delete this Student Enroll?</p>
                                                        <form action="{{ route('admin.offline.student.enroll.delete', $student['id']) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger my-2">Delete</button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->
                                @endforeach
                            </tbody>
                        </table>
                        <div class="modal fade" id="bs-importCertificate-modal" tabindex="-1" role="dialog" aria-labelledby="importMarkListLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="importMarkListLabel">Export</h4>
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
                                                    <p>Download the <a href="{{ asset('dashboard/assets/students.xlsx') }}" download>Sample Excel File</a> for Student Enrollment</p>
                                                </div>

                                                <form action="{{ route('admin.offline.student.import') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label class="form-label">Upload Excel File</label>
                                                        <input type="file" name="file" class="form-control" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Export</button>
                                                </form>
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->
                            </div>
                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div> <!-- end col -->
            </div>
            <!-- end row -->
@endsection
