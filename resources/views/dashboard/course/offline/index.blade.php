@extends('layouts.dashboard')
@section('list-offline-courses')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Courses</a></li>
                        <li class="breadcrumb-item active"> Add Offline Courses</li>
                    </ol>
                </div>
                <h4 class="page-title"> Add Offline Courses</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('offline-courses', 'create'))
                            <div class="col-sm-5">
                                <a href="{{ route('admin.offline.course.create') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
                            </div>
                        @endif
                        <div class="col-sm-7">
                        </div><!-- end col-->
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-borderless table-hover w-100 dt-responsive nowrap" id="products-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>Cover</th>
                                    <th>Name</th>
                                    <th>Short Description</th>
                                    <th>Total Fee</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list_main as $list)
                                    <tr>
                                        <td class="table-user">
                                            @if ($list['cover_image'])
                                                <img src="{{ env('STORAGE_URL') . '/' . $list['cover_image']  }}" class="me-2 rounded-circle">
                                            @else
                                                <span class="small text-danger">No Image</span>
                                            @endif
                                        </td>
                                        <td>{{ $list['name'] }}</td>
                                        <td>{{ Str::limit($list['short_description'], 30, '...') }}</td>
                                        <td>{{ $list['total_fee'] }}</td>
                                        <td>{{ $list['duration'] }}</td>
                                        <td>
                                            <div>
                                                <input type="checkbox" id="switch{{ $list['id'] }}" data-id="{{ $list['id'] }}" class="status-toggle" {{ $list['status'] == 1 ? 'checked' : '' }}  data-switch="success"/>
                                                <label for="switch{{ $list['id'] }}" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                                            </div>
                                        </td>
                                        <td>
                                            @if(auth()->user()->hasPermission('offline-courses', 'edit'))
                                                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-editOfflineCourse-modal{{ $list['id'] }}">
                                                    <i class="mdi mdi-square-edit-outline"></i>
                                                </a>
                                            @endif
                                            @if(auth()->user()->hasPermission('offline-courses', 'delete'))
                                                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal{{ $list['id'] }}">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Edit Modal-->
                                    <div class="modal fade" id="bs-editOfflineCourse-modal{{ $list['id'] }}" tabindex="-1" role="dialog" aria-labelledby="editOfflineCourseLabel{{ $list['id'] }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="editOfflineCourseLabel{{ $list['id'] }}">Edit Offline Course</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.offline.course.update', $list['id']) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf   
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="name" class="form-label">Name</label>
                                                                    <input type="text" name="name"  value="{{ $list['name'] }}" class="form-control"  id="name"  placeholder="Enter Name" >
                                                                </div>
                        
                                                                <div class="mb-3">
                                                                    <label for="total_fee" class="form-label">Total Fee</label>
                                                                    <input type="text" name="total_fee"  value="{{ $list['total_fee'] }}" class="form-control"  id="total_fee"  placeholder="Total Fee"  >
                                                                </div>
                        
                                                                <div class="mb-3">
                                                                    <label for="advance_fee" class="form-label">Advance Fee</label>
                                                                    <input type="text" name="advance_fee"  value="{{ $list['advance_fee'] }}" class="form-control"  id="advance_fee"  placeholder="Enter Advance Fee"  >
                                                                </div>
                        
                                                                <div class="mb-3">
                                                                    <label for="monthly_fee" class="form-label">Monthly Fee</label>
                                                                    <input type="text" name="monthly_fee"  value="{{ $list['monthly_fee'] }}" class="form-control"  id="monthly_fee"  placeholder="Enter Monthly Fee" >
                                                                </div>
                        
                                                                <div class="mb-3">
                                                                    <label for="example-textarea" class="form-label">Short Description</label>
                                                                    <textarea class="form-control" name="short_description" id="example-textarea" rows="4">{{ $list['short_description'] }}</textarea>
                                                                </div>
                        
                                                                <di class="mb-3">
                                                                    <label for="status" class="form-label">Status: </label></br/>
                                                                    <input type="hidden" name="status" id="hidden_status_{{ $list['id'] }}" value="{{ $list['status'] }}">
                                                                    <!-- Ensure each checkbox has a unique ID -->
                                                                    <input type="checkbox" class="status-toggle" id="status-toggle-{{ $list['id'] }}" data-id="{{ $list['id'] }}" value="1"  
                                                                    {{ $list['status'] == 1 ? 'checked' : '' }} data-switch="success" />
                                                                    <label for="status-toggle-{{ $list['id'] }}" data-on-label="" data-off-label=""></label>                                             
                                                                </div>  
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="base_name" class="form-label">Base Name</label>
                                                                    <input type="text" name="base_name"  value="{{ $list['base_name'] }}" class="form-control"  id="base_name"  placeholder="Enter Base Name" >
                                                                </div>
                        
                                                                <div class="mb-3">
                                                                    <label for="duration" class="form-label">Duration</label>
                                                                    <input type="text" name="duration"  value="{{ $list['duration'] }}" class="form-control"  id="duration"  placeholder="Duration"  >
                                                                </div>
                        
                                                                <div class="mb-3">
                                                                    <label for="monthly_fee_duration" class="form-label">Monthly Fee Duration</label>
                                                                    <input type="text" name="monthly_fee_duration"  value="{{ $list['monthly_fee_duration'] }}" class="form-control"  id="monthly_fee_duration"  placeholder="Enter Monthly Fee Duration" >
                                                                </div>
                        
                                                                <div class="mb-3 ">
                                                                    <label for="cover_image" class="form-label">Upload Cover Image</label>
                                                                    <input type="file" class="form-control" id="cover_image" name="cover_image">
                                                                </div>                                                                 
                        
                                                                <div class="mb-3">
                                                                    <label for="example-textarea" class="form-label">Description</label>
                                                                    <textarea class="form-control" name="full_description" id="example-textarea" rows="4">{{ $list['full_description'] }}</textarea>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="image" class="form-label">Current Cover Image</label><br>
                                                                    @if ($list['cover_image'])
                                                                        <img src="{{ env('STORAGE_URL') . '/' . $list['cover_image'] }}" class="me-2 img-fluid avatar-xl">
                                                                    @else
                                                                        <span class="small text-danger">No Cover Image</span>
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
                                    <div id="delete-alert-modal{{ $list['id'] }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-body p-4">
                                                    <div class="text-center">
                                                        <i class="ri-information-line h1 text-info"></i>
                                                        <h4 class="mt-2">Heads up!</h4>
                                                        <p class="mt-3">Do you want to delete this Offline Course?</p>
                                                        <form action="{{ route('admin.offline.course.delete', $list['id']) }}" method="POST">
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
                let courseoffId = $(this).data('id');
                let status = $(this).is(':checked') ? 1 : 0;

                // Update hidden input field before form submission
                $('#hidden_status_' + courseoffId).val(status);

                // Send AJAX request
                $.ajax({
                    url: "{{ route('admin.offline.course.update', ':id') }}".replace(':id', courseoffId),
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
