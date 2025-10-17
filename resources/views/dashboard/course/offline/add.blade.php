@extends('layouts.dashboard')
@section('add-offline-courses')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Courses</a></li>
                        <li class="breadcrumb-item active">Add Offline Courses</li>
                    </ol>
                </div>
                <h4 class="page-title">Add Offline Courses</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->  

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Add Offline Course</h4>    
                    <div class="row justify-content-center">
                        @if ($message = session()->get('message'))
                            <div class="alert alert-success text-center w-75">
                                <h6 class="text-center fw-bold">{{ $message }}...</h6>
                            </div>
                        @endif  
                    </div>
                                            
                    <div class="tab-content">
                        <div class="tab-pane show active" id="custom-styles-preview">
                            <form class="needs-validation" id="OfflineCourseForm" method="POST" action="{{ route('admin.offline.course.store') }}" enctype="multipart/form-data"  novalidate>
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" name="name"  value="{{ old('name') }}" class="form-control"  id="name"  placeholder="Enter Name" required>
                                            @error('name')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="total_fee" class="form-label">Total Fee</label>
                                            <input type="text" name="total_fee"  value="{{ old('total_fee') }}" class="form-control"  id="total_fee"  placeholder="Total Fee" required >
                                            @error('total_fee')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="advance_fee" class="form-label">Advance Fee</label>
                                            <input type="text" name="advance_fee"  value="{{ old('advance_fee') }}" class="form-control"  id="advance_fee"  placeholder="Enter Advance Fee"  required>
                                            @error('advance_fee')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="monthly_fee" class="form-label">Monthly Fee</label>
                                            <input type="text" name="monthly_fee"  value="{{ old('monthly_fee') }}" class="form-control"  id="monthly_fee"  placeholder="Enter Monthly Fee" required>
                                            @error('monthly_fee')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="example-textarea" class="form-label">Short Description</label>
                                            <textarea class="form-control" name="short_description" value="{{ old('short_description') }}" id="example-textarea" rows="4"></textarea>
                                            @error('short_description')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status: </label></br/>
                                            <input  type="checkbox" name="status"  id="switch3"  value="1"  checked  data-switch="success" onchange="this.value = this.checked ? 1 : 0;" />
                                            <label for="switch3" data-on-label="" data-off-label=""></label>
                                        </div>

                                    </div>  

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="base_name" class="form-label">Base Name</label>
                                            <input type="text" name="base_name"  value="{{ old('base_name') }}" class="form-control"  id="base_name"  placeholder="Enter Base Name" required>
                                            @error('base_name')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="duration" class="form-label">Duration</label>
                                            <input type="text" name="duration"  value="{{ old('duration') }}" class="form-control"  id="duration"  placeholder="Duration"  required>
                                            @error('duration')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="monthly_fee_duration" class="form-label">Monthly Fee Duration</label>
                                            <input type="text" name="monthly_fee_duration"  value="{{ old('monthly_fee_duration') }}" class="form-control"  id="monthly_fee_duration"  placeholder="Enter Monthly Fee Duration" required>
                                            @error('monthly_fee_duration')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3 ">
                                            <label for="cover_image" class="form-label">Upload Cover Image</label>
                                            <input type="file" class="form-control" id="cover_image" name="cover_image">
                                        </div>                                                                 

                                        <div class="mb-3">
                                            <label for="example-textarea" class="form-label">Description</label>
                                            <textarea class="form-control" name="full_description" value="{{ old('full_description') }}" id="example-textarea" rows="4"></textarea>
                                            @error('full_description')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
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