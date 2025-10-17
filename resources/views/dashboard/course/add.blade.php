@extends('layouts.dashboard')
@section('add-courses')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Courses</a></li>
                        <li class="breadcrumb-item active">Add Courses</li>
                    </ol>
                </div>
                <h4 class="page-title">Add Courses</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->  

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Add Course</h4>    
                    <div class="row justify-content-center">
                        @if ($message = session()->get('message'))
                            <div class="alert alert-success text-center w-75">
                                <h6 class="text-center fw-bold">{{ $message }}...</h6>
                            </div>
                        @endif  
                    </div>
                                            
                    <div class="tab-content">
                        <div class="tab-pane show active" id="custom-styles-preview">
                            <form class="needs-validation" id="CourseForm" method="POST" action="{{ route('admin.course.store') }}" enctype="multipart/form-data"  novalidate>
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name<span style="color:red">*</span></label>
                                            <input type="text" name="name"  value="{{ old('name') }}" class="form-control"  id="name"  placeholder="Enter Name" required>
                                            @error('name')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="course_fee" class="form-label">Fee</label>
                                            <input type="text" name="course_fee"  value="{{ old('course_fee') }}" class="form-control"  id="course_fee"  placeholder="Fee"  >
                                            @error('course_fee')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="toolkit_fee" class="form-label">Toolkit Fee</label>
                                            <input type="text" name="toolkit_fee"  value="{{ old('toolkit_fee') }}" class="form-control"  id="toolkit_fee"  placeholder="Enter toolkit fee"  >
                                            @error('toolkit_fee')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="target_audience" class="form-label">Target Audience</label>
                                            <input type="text" name="target_audience"  value="{{ old('target_audience') }}" class="form-control"  id="target_audience"  placeholder="Enter Target Audience" >
                                            @error('target_audience')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3 ">
                                            <label for="cover_image_web" class="form-label">cover image web</label>
                                            <input type="file" class="form-control" id="cover_image_web" name="cover_image_web">
                                        </div>                                                                 


                                        <div class="mb-3">
                                            <label for="example-textarea" class="form-label">Short Description</label>
                                            <textarea class="form-control" name="short_description" value="{{ old('short_description') }}" id="example-textarea" rows="3"></textarea>
                                            @error('short_description')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                    </div>  

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="total_hours" class="form-label">Total Hours</label>
                                            <input type="text" name="total_hours"  value="{{ old('total_hours') }}" class="form-control"  id="total_hours"  placeholder="Enter Total Hours"  >
                                            @error('total_hours')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="languages" class="form-label">Languages</label>                                                  
                                            <input type="text" class="form-control" name="languages" placeholder="Malayalam, English">
                                        </div>
                                        <!-- <div class="mb-3">
                                            <label for="tags" class="form-label">Tags</label>                                                  
                                            <input type="text" class="form-control" name="tags" placeholder="#tags1, #tag2">
                                        </div> -->
                                        <div class="mb-3 ">
                                            <label for="cover_image_mobile" class="form-label">cover image mobile</label>
                                            <input type="file" class="form-control" id="cover_image_mobile" name="cover_image_mobile">
                                        </div>                                                                 

                                        <div class="mb-3">
                                            <label for="example-textarea" class="form-label">Description</label>
                                            <textarea class="form-control" name="full_description" value="{{ old('full_description') }}" id="example-textarea" rows="4"></textarea>
                                            @error('full_description')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status: </label></br/>
                                            <input  type="checkbox" name="status"  id="switch3"  value="1"  checked  data-switch="success" onchange="this.value = this.checked ? 1 : 0;" />
                                            <label for="switch3" data-on-label="" data-off-label=""></label>
                                        </div>

                                    </div>
                                </div> 

                                <!-- Submit Button -->
                                <div class="text-start">
                                    <button type="submit" class="btn btn-danger">Reset</button>
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </form>
                        </div> <!-- end preview-->
                    </div> <!-- end tab-content-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
@endsection