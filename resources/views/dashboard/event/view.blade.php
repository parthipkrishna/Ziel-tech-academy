@extends('layouts.dashboard')
@section('view-event')

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Events</a></li>
                            <li class="breadcrumb-item active">Event Detail</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Event Detail</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="page-title">Event Details</h4>
                        <div class="tab-content">
                            <div class="tab-pane show active" id="custom-styles-preview">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom01">Name</label>
                                            <input type="text" name="name" value="{{ $event->name }}" class="form-control" id="validationCustom01" placeholder="Name" >
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom02">date</label>
                                            {{-- <input type="date" name="date" value="{{ $event->date }}" class="form-control" id="validationCustom02" placeholder="date" > --}}
                                            <input type="date" name="date" value="{{ \Carbon\Carbon::parse($event->date)->format('Y-m-d') }}" class="form-control" id="validationCustom02">

                                        </div>

                                    </div>

                                    <div class="col-lg-6">                        
                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom03">location</label>
                                            <input type="text" name="location" value="{{ $event->location }}" class="form-control" id="validationCustom03" placeholder="location" >
                                        </div>                        

                                        <div class="mb-3">
                                            <label for="example-textarea" class="form-label">Description</label>
                                            <textarea class="form-control" name="description" id="description" rows="2">{{ $event->description }}</textarea>
                                        </div>

                                    </div>
                                </div>
                            </div> <!-- end preview-->
                        </div> <!-- end tab-content-->
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->
    
    
    
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-sm-5">
                                <a href="javascript:void(0);" class="btn btn-danger mb-2" data-bs-toggle="modal" data-bs-target="#bs-addRole-modal">
                                    <i class="mdi mdi-square-edit-outline"></i> Add
                                </a>
                            </div>
                            <div class="col-sm-7">
                            </div><!-- end col-->
                        </div>
                        <div class="table-responsive">
                            <table class="table table-centered table-borderless table-hover w-100 dt-responsive nowrap" id="products-datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Media</th>
                                        <th>Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($event_media as $media)
                                        <tr>
                                            <td>
                                            @if ($media->type === 'image')
                                                <img src="{{ asset('storage/' . $media->media_url) }}" alt="Image" width="100">
                                            @elseif ($media->type === 'video')
                                                <video width="150" controls>
                                                    <source src="{{ asset('storage/' . $media->media_url) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @elseif ($media->type === 'youtube')
                                                <a href="{{ $media->media_url }}" target="_blank">
                                                    {{ $media->media_url }}
                                                </a>
                                            @endif
                                            </td>
                                            <td>{{ $media->type }}</td>                                        
                                            <td>
                                                <a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal{{ $media->id }}">
                                                    <i class="mdi mdi-delete"></i>
                                                </a>
                                            </td>
                                        </tr>
        
                                        <!-- Delete Alert Modal  -->
                                        <div id="delete-alert-modal{{ $media->id }}" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-body p-4">
                                                        <div class="text-center">
                                                            <i class="ri-information-line h1 text-info"></i>
                                                            <h4 class="mt-2">Heads up!</h4>
                                                            <p class="mt-3">Do you want to delete this Media?</p>
                                                            <form action="{{ route('admin.event.media.delete', $media->id) }}" method="POST">
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

                                    <!-- Import Modal -->
                                    <div class="modal fade" id="bs-addRole-modal" tabindex="-1" role="dialog" aria-labelledby="addRoleLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="addRoleLabel">Add Media</h4>
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
                                    
                                                    <form action="{{ route('admin.event.media.store') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="mb-3">
                                                                <label for="type" class="form-label">Media Type </label>
                                                                <select class="form-select mb-3" name="type" {{ old('type') }}id="type" required>
                                                                    <option selected>Select Media Type</option>
                                                                    <option value="image">IMAGE</option>
                                                                    <option value="video">VIDEO</option>
                                                                    <option value="youtube">YOUTUBE</option>

                                                                </select>
                                                            </div>
                                                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                                                            <!-- Image Upload Section -->
                                                            <div class="mb-3" id="image-upload-section" style="display:none;">
                                                                <label for="image" class="form-label">Image</label>
                                                                <div class="dropzone" id="ImageDropzone" data-plugin="dropzone">
                                                                    <div class="fallback">
                                                                        <input type="file" name="image" accept="image/*">

                                                                    </div>
                                                                    <div class="dz-message needsclick">
                                                                        <i class="h1 text-muted ri-upload-cloud-2-line"></i>
                                                                        <h4>Drop files here or click to upload.</h4>
                                                                    </div>
                                                                </div>
                                                                <!-- Preview -->
                                                                <div class="dropzone-previews mt-3" id="file-previews"></div>
                                                            </div>
                            
                                                            <!-- Video Upload Section -->
                                                            <div class="mb-3" id="video-upload-section" style="display:none;">
                                                                <label for="video" class="form-label">Video</label>
                                                                <div class="dropzone" id="VideoDropzone" data-plugin="dropzone">
                                                                    <div class="fallback">
                                                                        <input name="video" type="file" accept="video/*" />
                                                                    </div>
                                                                    <div class="dz-message needsclick">
                                                                        <i class="h1 text-muted ri-upload-cloud-2-line"></i>
                                                                        <h4>Drop video files here or click to upload.</h4>
                                                                    </div>
                                                                </div>
                                                                <!-- Preview -->
                                                                <div class="dropzone-previews mt-3" id="video-previews"></div>
                                                            </div>
                            
                                                            <!-- Youtube Upload Section -->
                                                            <div class="mb-3" id="youtube-upload-section" style="display:none;">
                                                                <div class="mb-3">
                                                                    <label for="youtube" class="form-label">Youtube</label>
                                                                    <input type="text" name="youtube" value="" class="form-control" id="link" placeholder="Enter link">
                                                                </div>
                                                            </div>
                                                            
                            
                                                        </div>
                                                        <!-- Submit Button -->
                                                        <div class="text-start">
                                                            <button type="reset" class="btn btn-danger">Reset</button>
                                                            <button type="submit" class="btn btn-primary">Create</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div><!-- /.modal-content -->
                                        </div><!-- /.modal-dialog -->
                                    </div><!-- /.modal -->
                                    
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>
        <!-- end row -->
            

@endsection