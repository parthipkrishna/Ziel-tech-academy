@extends('lms.layout.layout')
@section('add-important_links')

    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Important Link</a></li>
                        <li class="breadcrumb-item active">Add Important Link</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Add Important Link</h4>

                    @if ($message = session()->get('message'))
                        <div class="alert alert-success text-center w-75">
                            <h6 class="fw-bold">{{ $message }}...</h6>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger text-center w-75">
                            @foreach ($errors->all() as $error)
                                <h6 class="fw-bold">{{ $error }}</h6>
                            @endforeach
                        </div>
                    @endif

                    <form id="ImportantLinkForm" method="POST" action="{{ route('lms.store.important.link') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">

                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Enter Name" required>
                                    @error('name')
                                        <p class="small text-danger">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Link</label>
                                    <input type="text" name="link" value="{{ old('link') }}" class="form-control" placeholder="Enter Link" required>
                                    @error('link')
                                        <p class="small text-danger">{{$message}}</p>
                                    @enderror
                                </div>                                
                            </div>

                            <div class="col-lg-6">

                                <div class="mb-3">
                                    <label for="example-textarea" class="form-label">Short Description</label>
                                    <textarea class="form-control" name="short_description"  id="example-textarea" rows="2">{{ old('short_description') }}</textarea>
                                    @error('short_description')
                                        <p class="small text-danger">{{$message}}</p>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status: </label></br/>
                                        <input type="hidden" name="status" value="0">
                                    <input  type="checkbox" name="status"  id="switch3"  value="1"  checked  data-switch="success" onchange="this.value = this.checked ? 1 : 0;" />
                                    <label for="switch3" data-on-label="" data-off-label=""></label>
                                </div>

                            </div>
                        </div>
                        
                        <div class="text-start">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                var validator = $("#ImportantLinkForm").validate({
                    rules: {
                        name: {
                            required: true,
                            minlength: 3
                        },
                        link: {
                            required: true,
                            minlength: 3
                        },
                        short_description: {
                            required: false,
                            minlength: 0,
                            maxlength: 300
                        }
                    },
                    messages: {
                        name: {
                            required: "Name is required",
                            minlength: "Name must be at least 3 characters long"
                        },
                        link: {
                            required: "Link is required",
                            minlength: "Link must be at least 3 characters long"
                        },
                        short_description: {
                            required: false,
                            minlength: 0,
                            maxlength: "Short description cannot exceed 300 characters"
                        }
                    },
                    errorPlacement: function (error, element) {
                            error.addClass("text-danger").insertAfter(element);
                        },
                        highlight: function (element) {
                            $(element).addClass("is-invalid").removeClass("is-valid");
                        },
                        unhighlight: function (element) {
                            $(element).removeClass("is-invalid").addClass("is-valid");
                        },
                        onkeyup: function (element) {
                            $(element).valid();
                        },
                        onfocusout: function (element) {
                            $(element).valid();
                        }
                });

                $("#ImportantLinkForm button[type='submit']").click(function (event) {
                    if (!$("#ImportantLinkForm").valid()) {
                        validator.focusInvalid();
                        event.preventDefault();
                    }
                });
            
            });

        //validation #BannersForm
        $(document).ready(function () {
                $('#ImportantLinkForm').submit(function (e) {
                    e.preventDefault();

                    let formData = new FormData(this);

                    // clear previous errors
                    $('#modal-error-list').html('');
                    $('#modal-success-message').html('');
                    $('.is-invalid').removeClass('is-invalid');

                    $.ajax({
                        type: 'POST',
                        url: "{{ route('lms.store.important.link') }}",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            $('#modal-success-message').text(response.message ?? 'Link created successfully!');
                            let successModal = new bootstrap.Modal(document.getElementById('success-alert-modal'));
                            successModal.show();
                            $('#ImportantLinkForm')[0].reset();
                            setTimeout(() => {
                                                window.location.href = "{{ route('lms.important.links') }}";
                                            }, 1500);
                        },
                       error: function (xhr) {
                            console.log(xhr.responseJSON); // 👈 check what Laravel is returning
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                let errorHtml = '<ul class="list-unstyled text-start">';
                                $.each(errors, function (field, messages) {
                                    errorHtml += '<li>' + messages[0] + '</li>';
                                    $('[name="'+field+'"]').addClass('is-invalid');
                                });
                                errorHtml += '</ul>';
                                $('#modal-error-list').html(errorHtml);
                            } else {
                                $('#modal-error-list').html('<p>' + (xhr.responseJSON.message || 'Something went wrong') + '</p>');
                            }
                            let errorModal = new bootstrap.Modal(document.getElementById('danger-alert-modal'));
                            errorModal.show();
                        }
                });
            });
        });
        </script>
    @endpush
@endsection
