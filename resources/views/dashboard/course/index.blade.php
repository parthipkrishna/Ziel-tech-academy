@extends('layouts.dashboard')
@section('list-courses')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Courses</a></li>
                        <li class="breadcrumb-item active">Courses</li>
                    </ol>
                </div>
                <h4 class="page-title">Courses</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        @if(auth()->user()->hasPermission('online-courses', 'create'))
                            <div class="col-sm-5">
                                <a href="{{ route('admin.course.create') }}" class="btn btn-danger mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add </a>
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
                                    <th>Fee</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($courses as $course)
                                    <tr>
                                        <td class="table-user">
                                            @if ($course->cover_image_web)
                                                <img src="{{ env('STORAGE_URL') . '/' . $course->cover_image_web }}" class="me-2 rounded-circle">
                                            @else
                                                <span class="small text-danger">No Image</span>
                                            @endif
                                        </td>
                                        <td>{{ $course->name }}</td>
                                        <td>
                                            @if ($course->short_description)
                                                {{ Str::limit($course->short_description, 25, '...')  }}
                                            @else
                                                <span class="small text-danger">No Short Decription</span>
                                            @endif 
                                        </td>
                                        <td>
                                            @if ($course->course_fee)
                                                {{ Str::limit($course->course_fee, 25, '...')  }}
                                            @else
                                                <span class="small text-danger">No Course Fee</span>
                                            @endif 
                                        </td>
                                        <td>
                                            <div>
                                                <input type="checkbox" id="switch{{ $course->id }}" data-id="{{ $course->id }}" class="status-toggle" {{ $course->status == 1 ? 'checked' : '' }}  data-switch="success"/>
                                                <label for="switch{{ $course->id }}" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                                            </div>
                                        </td>
                                        <td>
                                            @if(auth()->user()->hasPermission('online-courses', 'edit'))
                                                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-editCourse-modal{{ $course->id }}">
                                                    <i class="mdi mdi-square-edit-outline"></i>
                                                </a>
                                            @endif
                                            @if(auth()->user()->hasPermission('online-courses', 'delete'))
                                                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal{{ $course->id }}">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Edit Modal-->
                                    <div class="modal fade" id="bs-editCourse-modal{{ $course->id }}" tabindex="-1" role="dialog" aria-labelledby="editCourseLabel{{ $course->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="editCourseLabel{{ $course->id }}">Edit online Course</h4>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.course.update', $course->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="name" class="form-label">Name<span style="color:red">*</span></label>
                                                                    <input type="text" name="name"  value="{{ $course->name }}" class="form-control"  id="name"  placeholder="Enter Name" required>
                                                                </div>
                        
                                                                <div class="mb-3">
                                                                    <label for="course_fee" class="form-label">Fee</label>
                                                                    <input type="text" name="course_fee"  value="{{ $course->course_fee }}" class="form-control"  id="course_fee"  placeholder="Fee"  >
                                                                </div>
                        
                                                                <div class="mb-3">
                                                                    <label for="toolkit_fee" class="form-label">Toolkit Fee</label>
                                                                    <input type="text" name="toolkit_fee"  value="{{ $course->toolkit_fee }}" class="form-control"  id="toolkit_fee"  placeholder="Enter toolkit fee"  >
                                                                </div>
                        
                                                                <div class="mb-3">
                                                                    <label for="target_audience" class="form-label">Target Audience</label>
                                                                    <input type="text" name="target_audience"  value="{{ $course->target_audience }}" class="form-control"  id="target_audience"  placeholder="Enter Target Audience" >
                                                                </div>                        
                        
                                                                <div class="mb-3">
                                                                    <label for="example-textarea" class="form-label">Short Description</label>
                                                                    <textarea class="form-control" name="short_description" id="example-textarea" rows="5">{{ $course->short_description }}</textarea>
                                                                    @error('short_description')
                                                                        <p class="small text-danger">{{$message}}</p>
                                                                    @enderror
                                                                </div>

                                                                <div class="mb-3 ">
                                                                    <label for="cover_image_web" class="form-label">Upload Cover Image Web</label>
                                                                    <input type="file" class="form-control" id="cover_image_web" name="cover_image_web">
                                                                </div>                                                                 
                                                                <div class="mb-3">
                                                                    <label for="image" class="form-label">Current Image</label><br>
                                                                    @if ($course->cover_image_web)
                                                                        <img src="{{ env('STORAGE_URL') . '/' . $course->cover_image_web }}" class="me-2 img-fluid avatar-xl">
                                                                    @else
                                                                        <span class="small text-danger">No Image</span>
                                                                    @endif
                                                                </div>                      
                                                            </div>                         
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="total_hours" class="form-label">Total Hours</label>
                                                                    <input type="text" name="total_hours"  value="{{ $course->total_hours }}" class="form-control"  id="total_hours"  placeholder="Enter Total Hours"  >
                                                                </div>                      
                                                                <div class="mb-3">
                                                                    <label for="languages" class="form-label">Languages</label>                                                  
                                                                    <input type="text" class="form-control" name="languages" placeholder="Malayalam, English">
                                                                </div>
                                                                <!-- <div class="mb-3">
                                                                    <label for="tags" class="form-label">Tags</label>                                                  
                                                                    <input type="text" class="form-control" name="tags" placeholder="#tags1, #tag2">
                                                                </div>                                                                                                                                                     -->
                                                                <div class="mb-3">
                                                                    <label for="example-textarea" class="form-label">Description</label>
                                                                    <textarea class="form-control" name="full_description" id="example-textarea" rows="5">{{ $course->full_description }}</textarea>
                                                                </div>
                        
                                                                <div class="mb-3">
                                                                    <label for="status" class="form-label">Status: </label></br/>
                                                                    <input type="hidden" name="status" id="hidden_status_{{ $course['id'] }}" value="{{ $course['status'] }}">
                                                                    <!-- Ensure each checkbox has a unique ID -->
                                                                    <input type="checkbox" class="status-toggle" id="status-toggle-{{ $course->id }}" data-id="{{ $course->id }}" value="1"  
                                                                        {{ $course->status == 1 ? 'checked' : '' }} data-switch="success" />
                                                                    <label for="status-toggle-{{ $course->id }}" data-on-label="" data-off-label=""></label>
                                                                </div>
                                                                <div class="mb-3 ">
                                                                    <label for="cover_image_mobile" class="form-label">Upload Cover image mobile</label>
                                                                    <input type="file" class="form-control" id="cover_image_mobile" name="cover_image_mobile">
                                                                </div>   
                                                                <div class="mb-3">
                                                                    <label for="image" class="form-label">Current Image</label><br>
                                                                    @if ($course->cover_image_mobile)
                                                                        <img src="{{ env('STORAGE_URL') . '/' . $course->cover_image_mobile }}" class="me-2 img-fluid avatar-xl">
                                                                    @else
                                                                        <span class="small text-danger">No Image</span>
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
                                    <div id="delete-alert-modal{{ $course->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-sm">
                                            <div class="modal-content">
                                                <div class="modal-body p-4">
                                                    <div class="text-center">
                                                        <i class="ri-information-line h1 text-info"></i>
                                                        <h4 class="mt-2">Heads up!</h4>
                                                        <p class="mt-3">Do you want to delete this User?</p>
                                                        <form action="{{ route('admin.course.delete', $course->id) }}" method="POST">
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
    <script>
        $(document).ready(function () {
            $('.status-toggle').change(function () {
                let courseId = $(this).data('id');
                let status = $(this).is(':checked') ? 1 : 0;

                // Update hidden input field before form submission
                $('#hidden_status_' + courseId).val(status);

                // Send AJAX request
                $.ajax({
                    url: "{{ route('admin.course.update', ':id') }}".replace(':id', courseId),
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: status
                    }
                });
            });
        });
    </script>
    <!-- end row -->
@endsection
