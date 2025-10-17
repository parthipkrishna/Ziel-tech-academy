@extends('lms.layout.layout')
@section('add-influencer')


    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Influencers</a></li>
                        <li class="breadcrumb-item active">Add Influencer</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->  

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Add Influencer</h4>    
                    <div class="row justify-content-center">
                        @if ($message = session()->get('message'))
                            <div class="alert alert-success text-center w-75">
                                <h6 class="text-center fw-bold">{{ $message }}...</h6>
                            </div>
                        @endif  
                    </div>
                                            
                    <div class="tab-content">
                        <div class="tab-pane show active" id="custom-styles-preview">
                            <form class="needs-validation" id="InfluencerForm" method="POST" action="{{ route('lms.store.influencer') }}" enctype="multipart/form-data">
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
                                            <label for="email" class="form-label">Email</label>
                                            <input type="text" name="email"  value="{{ old('email') }}" class="form-control"  id="email"  placeholder="Enter Email"  >
                                            @error('email')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="text" name="phone"  value="{{ old('phone') }}" class="form-control"  id="phone"  placeholder="Enter Phone"  >
                                            @error('phone')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>  

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="kyc_docs" class="form-label">Kyc Docs</label>
                                            <input type="file" name="kyc_docs"  value="{{ old('kyc_docs') }}" class="form-control"  id="kyc_docs"  placeholder="Enter Kyc Docs"  >
                                            @error('kyc_docs')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>
                                       <div class="mb-3">
                                            <label for="commission_per_user" class="form-label">Commission</label>
                                            <input type="text" name="commission_per_user"  value="{{ old('commission_per_user') }}" class="form-control"  id="commission_per_user"  placeholder="Enter Commission per User"  >
                                            @error('commission_per_user')
                                                <p class="small text-danger">{{$message}}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-3 ">
                                            <label for="image" class="form-label">Profile Image</label>
                                            <input type="file" class="form-control" id="image" name="image">
                                        </div>                                                         
                                    </div>
                                </div> 

                                <!-- Submit Button -->
                                <div class="text-start">
                                    <button type="reset" class="btn btn-danger">Reset</button>
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </form>
                        </div> <!-- end preview-->
                    </div> <!-- end tab-content-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
    <script>
        //validation #BannersForm
        $(document).ready(function () {
                $('#InfluencerForm').submit(function (e) {
                    e.preventDefault();

                    let formData = new FormData(this);

                    // clear previous errors
                    $('#modal-error-list').html('');
                    $('#modal-success-message').html('');
                    $('.is-invalid').removeClass('is-invalid');

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('lms.store.influencer') }}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            $('#modal-success-message').text(response.message ?? 'Influencer created successfully!');
                            let successModal = new bootstrap.Modal(document.getElementById('success-alert-modal'));
                            successModal.show();
                            $('#InfluencerForm')[0].reset();
                            setTimeout(() => {
                                                window.location.href = "{{ route('lms.influencers') }}";
                                            }, 1500);
                        },
                       error: function(xhr) {
                            let errorHtml = '';
                            let modalTitle = 'Validation Error!';

                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                errorHtml = '<ul class="list-unstyled text-start">';

                                $.each(errors, function(field, messages) {
                                    // Only show errors that are NOT "required" errors
                                    let filtered = messages.filter(msg => !msg.toLowerCase().includes('required'));
                                    if (filtered.length > 0) {
                                        errorHtml += '<li>' + filtered[0] + '</li>';
                                    }
                                });

                                errorHtml += '</ul>';

                                // Only populate modal if there is at least one non-required error
                                if ($(errorHtml).text().trim().length > 0) {
                                    $('#modal-error-list').html(errorHtml);
                                    let errorModal = new bootstrap.Modal(document.getElementById('danger-alert-modal'));
                                    errorModal.show();
                                }

                            } else {
                                // Server errors (500, etc.)
                                let errorMsg = xhr.responseJSON?.error || xhr.responseJSON?.message || 'Something went wrong.';
                                $('#modal-error-list').html('<p>' + errorMsg + '</p>');
                                let errorModal = new bootstrap.Modal(document.getElementById('danger-alert-modal'));
                                errorModal.show();
                            }
                        }
                });
            });
        });
    </script>
@endsection 
