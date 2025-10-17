<!-- Edit Modal-->
<div class="modal fade" id="bs-editCourse-modal{{ $course->id }}" tabindex="-1" role="dialog" aria-labelledby="editCourseLabel{{ $course->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editCourseLabel{{ $course->id }}">Edit online Course</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('lms.update.course.section', $course->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name"  value="{{ $course->name }}" class="form-control"  id="name"  placeholder="Enter Name" required>
                            </div>             
                        </div>                         
                        <div class="col-lg-6">
                        
                            <div class="mb-3">
                                <label for="status_{{  $course->id }}" class="form-label">Status: </label></br/>
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" name="status" id="status_{{  $course->id }}" value="1"  {{  $course->status == 1 ? 'checked' : '' }}  data-switch="success" />
                                <label for="status_{{  $course->id }}" data-on-label="" data-off-label=""></label>
                            </div>
                        </div>
                    </div> 

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->