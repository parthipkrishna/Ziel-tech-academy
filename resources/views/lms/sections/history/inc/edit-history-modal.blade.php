<!-- Edit Modal-->
<div class="modal fade" id="bs-viewHistory-modal{{ $history->id }}" tabindex="-1" role="dialog" aria-labelledby="viewHistoryLabel{{ $history->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="viewHistoryLabel{{ $history->id }}">Detailed History</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('lms.update.course.section', $history->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="name" class="form-label">Referral Code</label>
                                <input type="text" name="name"  value="{{ $history->referralCode->code}}" class="form-control"  id="name"  placeholder="Enter Name" disabled>
                            </div>             
                        </div>       
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="name" class="form-label">Used At</label>
                                <input type="text" name="name"  value="{{  \Carbon\Carbon::parse($history->used_at)->format('d M Y, h:i A')}}" class="form-control"  id="name"  placeholder="Enter Name" disabled>
                            </div>             
                        </div>                  
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="name" class="form-label">Status</label><br/>
                                @if($history->status== 'processing')
                                    <span class="badge bg-primary" disabled>Processing</span>
                                @elseif ($history->status== 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @else
                                    <span class="badge bg-success">Onboarded</span>
                                @endif
                            </div>
                        </div>
                    </div> 

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title mb-3">Influencer Information</h4>

                                    <ul class="list-unstyled mb-0">
                                        <li>
                                            <p>
                                                @if ($history->referralCode->influencer->image)
                                                    <img src="{{ env('STORAGE_URL') . '/' . $history->referralCode->influencer->image }}" class="me-2 img-fluid avatar-lg rounded">
                                                @else
                                                    <img src="{{ asset('lms/assets/images/avathar.png') }}" class="me-2 img-fluid avatar-lg rounded" alt="Default Avatar">
                                                @endif
                                            </p>
                                            <p class="mb-2"><span class="fw-bold me-2">Name :</span>{{ $history->referralCode->influencer->name }}</p>
                                            <p class="mb-2"><span class="fw-bold me-2">Email :</span>{{ $history->referralCode->influencer->email }}</p>
                                            <p class="mb-2"><span class="fw-bold me-2">Phone :</span>{{ $history->referralCode->influencer->phone }}</p>
                                            <p class="mb-2"><span class="fw-bold me-2">Commission per User :</span>{{ $history->referralCode->influencer->commission_per_user }} </p>
                                        </li>
                                    </ul>

                                </div>
                            </div>
                        </div> <!-- end col -->

                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title mb-3">Student Information</h4>
                                   <ul class="list-unstyled mb-0">
                                        <li>
                                            <p>
                                                @if ($history->student?->image)
                                                    <img src="{{ env('STORAGE_URL') . '/' . $history->student->image }}" 
                                                        class="me-2 img-fluid avatar-lg rounded">
                                                @else
                                                    <img src="{{ asset('lms/assets/images/avathar.png') }}" 
                                                        class="me-2 img-fluid avatar-lg rounded" alt="Default Avatar">
                                                @endif
                                            </p>

                                            <p class="mb-2">
                                                <span class="fw-bold me-2">Name :</span>
                                                {{ $history->student?->name ?? 'Not Available' }}
                                            </p>

                                            <p class="mb-2">
                                                <span class="fw-bold me-2">Email :</span>
                                                {{ $history->student?->email ?? 'Not Available' }}
                                            </p>

                                            <p class="mb-2">
                                                <span class="fw-bold me-2">Phone :</span>
                                                {{ $history->student?->phone ?? 'Not Available' }}
                                            </p>

                                            @if ($history->student?->studentProfile)
                                                <p class="mb-2">
                                                    <span class="fw-bold me-2">Admission Number :</span>
                                                    {{ $history->student->studentProfile->admission_number }}
                                                </p>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->