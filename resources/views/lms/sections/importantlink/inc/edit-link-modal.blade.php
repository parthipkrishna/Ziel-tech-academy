<!-- Edit Modal-->
<div class="modal fade" id="bs-editImportantLink-modal{{ $link->id }}" tabindex="-1" role="dialog" aria-labelledby="editImportantLinkLabel{{ $link->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editImportantLinkLabel{{ $link->id }}">Edit Important Link</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('lms.update.important.link', $link->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">

                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" value="{{ $link->name }}" class="form-control" placeholder="Enter Name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Link</label>
                                <input type="text" name="link" value="{{ $link->link }}" class="form-control" placeholder="Enter Link">
                            </div>  
                                
                        </div>   

                        <div class="col-lg-6">

                            <div class="mb-3">
                                <label for="example-textarea" class="form-label">Short Description</label>
                                <textarea class="form-control" name="short_description"  id="example-textarea" rows="3">{{ $link->short_description }}</textarea>
                                @error('short_description')
                                    <p class="small text-danger">{{$message}}</p>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status_{{  $link->id }}" class="form-label">Status: </label></br/>
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" name="status" id="status_{{  $link->id }}" value="1"  {{  $link->status == 1 ? 'checked' : '' }}  data-switch="success" />
                                <label for="status_{{  $link->id }}" data-on-label="" data-off-label=""></label>
                            </div> 
                        </div>

                    </div> 

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->