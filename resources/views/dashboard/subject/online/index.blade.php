@extends('layouts.dashboard')
@section('list-subjects')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Ziel-Tech</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Subjects</a></li>
                        <li class="breadcrumb-item active"> Subjects</li>
                    </ol>
                </div>
                <h4 class="page-title"> Subjects</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('online-subjects', 'create'))
                            <div class="col-sm-5">
                                <a href="{{ route('admin.subject.create') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                            </div>
                        @endif
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-borderless table-hover w-100 dt-responsive nowrap" id="products-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>Subject</th>
                                    <th>Name</th>
                                    <th>Course</th>
                                    <th>Short Description</th>
                                    <th>Total Hours</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subject_main as $subject)
                                    <tr>
                                        <td class="table-user">
                                            @if ($subject['web_thumbnail'])
                                                <img src="{{ env('STORAGE_URL') . '/' . $subject['web_thumbnail']  }}" class="me-2 rounded-circle">
                                            @else
                                                <span class="small text-danger">No Image</span>
                                            @endif
                                        </td>
                                        <td>{{ $subject['name'] }}</td>
                                        <td>{{ $subject['course_name'] }}</td>
                                        <td>
                                            {{ $subject['short_desc'] ? Str::limit($subject['short_desc'], 30, '...') : 'No short description' }}
                                        </td>
                                        <td>{{ $subject['total_hours'] }}</td>
                                        <td>
                                            <div>
                                                <input type="checkbox" id="switch{{ $subject['id'] }}" data-id="{{ $subject['id'] }}" class="status-toggle" {{ $subject['status'] == 1 ? 'checked' : '' }}  data-switch="success"/>
                                                <label for="switch{{ $subject['id'] }}" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                                            </div>
                                        </td>
                                        <td>
                                            @if(auth()->user()->hasPermission('online-subjects', 'edit'))
                                                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-editBranch-modal{{ $subject['id'] }}">
                                                    <i class="mdi mdi-square-edit-outline"></i>
                                                </a>
                                            @endif
                                            @if(auth()->user()->hasPermission('online-subjects', 'delete'))
                                                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal{{ $subject['id'] }}">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Edit Modal-->
                                    <div class="modal fade" id="bs-editBranch-modal{{ $subject['id'] }}" tabindex="-1" role="dialog" aria-labelledby="editBranchLabel{{ $subject['id'] }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="editBranchLabel{{ $subject['id'] }}">Edit Shope User</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.subject.update', $subject['id']) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf   
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="validationCustom01">Name</label>
                                                                    <input type="text" name="name" value="{{  $subject['name'] }}" class="form-control" id="name" placeholder="Name" >
                                                                </div>
                        
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="validationCustom01">Total Hours</label>
                                                                    <input type="text" name="total_hours" value="{{  $subject['total_hours'] }}" class="form-control" id="validationCustom01" placeholder="Total Hours" >
                                                                </div>
                        
                                                                <div class="mb-3">
                                                                    <label for="district" class="form-label">Courses</label>
                                                                    <select class="form-select" id="example-select" name="course_id" >
                                                                        <option value="">{{  $subject['course_name'] }}</option>
                                                                        <option value="">Select option</option>
                                                                        @foreach ($courses as $item)
                                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                        
                                                                <div class="mb-3">
                                                                    <label for="web_thumbnail" class="form-label">Upload Web Thumbnail</label>
                                                                    <input type="file" name="web_thumbnail" class="form-control" >
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="image" class="form-label">Current Web Thumbnail</label><br>
                                                                    @if ($subject['web_thumbnail'])
                                                                        <img src="{{ env('STORAGE_URL') . '/' . $subject['web_thumbnail'] }}" class="me-2 img-fluid avatar-xl">
                                                                    @else
                                                                        <span class="small text-danger">No Web Thumbnail</span>
                                                                    @endif
                                                                </div>                 
                                                                <div class="mb-3">
                                                                    <label for="status" class="form-label">Status: </label></br/>
                                                                    <input type="hidden" name="status" id="hidden_status_{{ $subject['id'] }}" value="{{ $subject['status'] }}">
                                                                    <!-- Use array syntax to access the id -->
                                                                    <input type="checkbox" class="status-toggle" id="status-toggle-{{ $subject['id'] }}" data-id="{{ $subject['id'] }}" value="1"  
                                                                        {{ $subject['status'] == 1 ? 'checked' : '' }} data-switch="success" />
                                                                    <label for="status-toggle-{{ $subject['id'] }}" data-on-label="" data-off-label=""></label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="example-textarea" class="form-label">Short Description</label>
                                                                    <textarea class="form-control" name="short_desc" id="short_desc" rows="3" >{{ $subject['short_desc'] }}</textarea>
                                                                </div>
                        
                                                                <div class="mb-3">
                                                                    <label for="example-textarea" class="form-label">Description</label>
                                                                    <textarea class="form-control" name="desc" id="desc" rows="3" >{{ $subject['desc'] }}</textarea>
                                                                </div>
                        
                                                                <div class="mb-3">
                                                                    <label for="mobile_thumbnail" class="form-label">Upload Mobile Thumbnail</label>
                                                                    <input type="file" name="mobile_thumbnail" class="form-control" >
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="image" class="form-label">Current Mobile Thumbnail</label><br>
                                                                    @if ($subject['mobile_thumbnail'])
                                                                        <img src="{{ env('STORAGE_URL') . '/' . $subject['mobile_thumbnail'] }}" class="me-2 img-fluid avatar-xl">
                                                                    @else
                                                                        <span class="small text-danger">No Mobile Thumbnail</span>
                                                                    @endif
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
                                    <div id="delete-alert-modal{{ $subject['id'] }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-body p-4">
                                                    <div class="text-center">
                                                        <i class="ri-information-line h1 text-info"></i>
                                                        <h4 class="mt-2">Heads up!</h4>
                                                        <p class="mt-3">Do you want to delete this Subject?</p>
                                                        <form action="{{ route('admin.subject.delete', $subject['id']) }}" method="POST">
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
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <!-- end row -->
    <script>
        $(document).ready(function () {
            $('.status-toggle').change(function () {
                let subjectId = $(this).data('id');
                let status = $(this).is(':checked') ? 1 : 0;

                // Update hidden input field before form submission
                $('#hidden_status_' + subjectId).val(status);

                // Send AJAX request
                $.ajax({
                    url: "{{ route('admin.subject.update', ':id') }}".replace(':id', subjectId),
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: status
                    }
                });
            });
        });
    </script>

@endsection
