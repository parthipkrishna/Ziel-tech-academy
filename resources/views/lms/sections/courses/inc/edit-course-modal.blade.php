<!-- Edit Modal-->
<div class="modal fade" id="bs-editCourse-modal{{ $course->id }}" tabindex="-1" role="dialog" aria-labelledby="editCourseLabel{{ $course->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editCourseLabel{{ $course->id }}">Edit online Course</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('lms.update.course', $course->id) }}" method="POST" enctype="multipart/form-data">
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
                                <label for="course_end_date" class="form-label">Course Duration (Months)</label>
                                <input type="number" class="form-control" value="{{ $course->course_end_date }}" name="course_end_date" placeholder="Enter duration in months" required>
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
                                    <img src="{{ env('STORAGE_URL') . '/' . $course->cover_image_web }}" class="me-2 img-fluid" style="width: 300px; height: 200px; object-fit: cover;">
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
                                <input type="text" class="form-control" name="languages" value="{{ $course->languages }}"placeholder="Malayalam, English">
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
                                    <img src="{{ env('STORAGE_URL') . '/' . $course->cover_image_mobile }}" class="me-2 img-fluid" style="width: 300px; height: 200px; object-fit: cover;">
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