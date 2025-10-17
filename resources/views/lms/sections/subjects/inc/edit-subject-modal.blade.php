<!-- Edit Modal-->
<div class="modal fade" id="bs-editsubject-modal{{ $subject['id']}}" tabindex="-1" role="dialog" aria-labelledby="editsubjectLabel{{ $subject['id']}}" aria-hidden="true">
<div class="modal-dialog modal-lg">
<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title" id="editsubjectLabel{{ $subject['id']}}">Edit subject</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
    </div>
    <div class="modal-body">
        <form action="{{ route('lms.update.subject', $subject['id']) }}" method="POST" enctype="multipart/form-data">
            @csrf   
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">Name</label>
                        <input type="text" name="name"  value="{{ $subject['name']}}" class="form-control"  id="first_name"  placeholder="Enter Name" >
                    </div>

                    <div class="mb-3">
                        <label for="total_hours" class="form-label">Total Hours</label>
                        <input type="text" name="total_hours"  value="{{ $subject['total_hours'] }}" class="form-control"  id="total_hours"  placeholder="Enter Total Hours"  >
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

                    <div class="mb-3 ">
                        <label for="web_thumbnail" class="form-label">Web Thumbnail</label>
                        <input type="file" class="form-control" id="web_thumbnail" name="web_thumbnail">
                    </div> 

                    <div class="mb-3">
                        <label for="image" class="form-label">Current Web Thumbnail</label><br>
                        @if ($subject['web_thumbnail'])
                            <img src="{{ env('STORAGE_URL') . '/' . $subject['web_thumbnail'] }}" class="me-2 img-fluid" style="width: 300px; height: 200px; object-fit: cover;">
                        @else
                            <span class="small text-danger">No Image</span>
                        @endif
                    </div>

                </div>  

                <div class="col-lg-6">

                    <div class="mb-3">
                        <label for="example-textarea" class="form-label">Short Description</label>
                        <textarea class="form-control" name="short_desc" id="example-textarea" rows="3">{{ $subject['short_desc'] }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="example-textarea" class="form-label">Description</label>
                        <textarea class="form-control" name="desc" id="example-textarea" rows="3">{{ $subject['desc'] }}</textarea>
                    </div>

                    <div class="mb-3 ">
                        <label for="mobile_thumbnail" class="form-label">Mobile Thumbnail</label>
                        <input type="file" class="form-control" id="mobile_thumbnail" name="mobile_thumbnail">
                    </div> 

                    <div class="mb-3">
                        <label for="image" class="form-label">Current Mobile Thumbnail</label><br>
                        @if ($subject['mobile_thumbnail'])
                            <img src="{{ env('STORAGE_URL') . '/' . $subject['mobile_thumbnail'] }}" class="me-2 img-fluid" style="width: 300px; height: 200px; object-fit: cover;">
                        @else
                            <span class="small text-danger">No Image</span>
                        @endif
                    </div>   

                    <div class="mb-3">
                        <label for="status_{{  $subject['id']}}" class="form-label">Status: </label></br/>
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" name="status" id="status_{{  $subject['id']}}" value="1"  {{  $subject['status'] == 1 ? 'checked' : '' }}  data-switch="success" />
                        <label for="status_{{  $subject['id']}}" data-on-label="" data-off-label=""></label>
                    </div>

                </div>
            </div> 
                                                                    
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->