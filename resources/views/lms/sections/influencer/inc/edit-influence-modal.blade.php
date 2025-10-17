<!-- Edit Modal-->
<div class="modal fade" id="bs-editInfluencer-modal{{ $influencer['id']}}" tabindex="-1" role="dialog" aria-labelledby="editInfluencerLabel{{ $influencer['id']}}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editInfluencerLabel{{ $influencer['id']}}">Edit Influencer</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('lms.update.influencer', $influencer['id']) }}" method="POST" enctype="multipart/form-data">
                    @csrf   
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name"  value="{{ $influencer['name']}}" class="form-control"  id="name"  placeholder="Enter Name" >
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email"  value="{{ $influencer['email'] }}" class="form-control"  id="email"  placeholder="Enter Email"  >
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" name="phone"  value="{{ $influencer['phone']}}" class="form-control"  id="phone"  placeholder="Enter phone" >
                            </div>
                        </div>  

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="kyc_docs" class="form-label">Upload KYC Document</label>
                                <input type="file" name="kyc_docs" class="form-control" id="kyc_docs">
                            </div>

                            @if ($influencer['kyc_docs'])
                                <div class="mb-3">
                                    <label class="form-label">Current KYC Document</label><br>

                                    @php
                                        $kycPath = 'storage/' . $influencer['kyc_docs'];
                                        $kycExtension = strtolower(pathinfo($kycPath, PATHINFO_EXTENSION));
                                    @endphp

                                    @if (in_array($kycExtension, ['jpg', 'jpeg', 'png']))
                                        <img src="{{ asset($kycPath) }}" class="img-fluid border rounded" style="max-width: 200px;">
                                    @elseif ($kycExtension === 'pdf')
                                        <a href="{{ asset($kycPath) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                            View Document
                                        </a>
                                    @else
                                        <a href="{{ asset($kycPath) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
                                            Open Document
                                        </a>
                                    @endif
                                </div>
                            @endif
                            <div class="mb-3">
                                <label for="commission_per_user" class="form-label">Commission per User</label>
                                <input type="text" name="commission_per_user"  value="{{ $influencer['commission_per_user']}}" class="form-control"  id="commission_per_user"  placeholder="Enter Commission per User" >
                            </div>

                                <div class="mb-3 ">
                                <label for="image" class="form-label">Profile Image</label>
                                <input type="file" class="form-control" id="image" name="image">
                            </div>  
                            <div class="mb-3">
                                <label for="image" class="form-label">Current Profile Image</label><br>
                                @if ($influencer['image'])
                                    <img src="{{ env('STORAGE_URL') . '/' . $influencer['image'] }}" class="me-2 img-fluid avatar-xl">
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