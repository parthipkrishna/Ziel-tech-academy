@extends('lms.layout.layout')
@section('content')
<div class="content">
<div class="container-fluid">

  <!-- start page title -->
  <div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">User</a></li> 
                    <li class="breadcrumb-item active">Add Users</li>
                </ol>
            </div>
            <h4 class="page-title">Add User</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3"></h4>
                <div class="row justify-content-center">
                     {{-- Display general messages --}}
                     @if ($message = session()->get('message')) --
                        <div class="alert alert-success text-center w-75">
                            <h6 class="text-center fw-bold">{{ $message }}...</h6>
                        </div>
                    @endif
                    {{-- Display validation error messages --}}
                    @if ($errors->any())
                        <div class="alert alert-danger text-center w-75">
                            @foreach ($errors->all() as $error)
                                <h6 class="text-center fw-bold">{{ $error }}</h6>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="tab-content">
                    <div class="tab-pane show active" id="custom-styles-preview">
                      @include('lms.sections.user.inc.add-form')
                    </div> <!-- end preview-->
                </div> <!-- end tab-content-->
            </div> <!-- end card-body -->
        </div> <!-- end card -->
    </div><!-- end col -->
</div><!-- end row -->
</div>
</div>


<script>
    $(document).ready(function () {
    $('#userForm').submit(function (e) {
        e.preventDefault();
        let formData = new FormData(this);

        // Reset previous errors
        $('.invalid-feedback').text('');
        $('.form-control').removeClass('is-invalid');

        $.ajax({
            type: 'POST',
            url: "{{ route('lms.store.user') }}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                // Populate success message
                $('#modal-success-message').text(response.message || 'User created successfully');
                
                // Show modal
                var successModal = new bootstrap.Modal(document.getElementById('success-alert-modal'));
                successModal.show();

                // Optional: Reset form after success
                $('#userForm')[0].reset();

                // Optional: redirect after a delay
                setTimeout(() => {
                    window.location.href = "{{ route('lms.users') }}";
                }, 1500);
            },
            error: function (xhr) {
                let errorHtml = '';
                let modalTitle = 'Validation Error!';

                if (xhr.status === 422) {
                    // Validation errors
                    let errors = xhr.responseJSON.errors;
                    let filteredErrors = [];

                    $.each(errors, function (field, messages) {
                        // Only keep "has already been taken" messages
                        messages.forEach(msg => {
                            if (msg.toLowerCase().includes("has already been taken")) {
                                filteredErrors.push(msg);
                            }
                        });
                    });

                    if (filteredErrors.length > 0) {
                        errorHtml = '<ul class="list-unstyled text-start">';
                        filteredErrors.forEach(msg => {
                            errorHtml += '<li>' + msg + '</li>';
                        });
                        errorHtml += '</ul>';
                    } else {
                        // Do nothing (don’t show required errors)
                        return;
                    }

                } else {
                    // Server errors (500, DB, etc.)
                    modalTitle = 'An Unexpected Error Occurred!';
                    let errorMsg = xhr.responseJSON?.error || xhr.responseJSON?.message || 'The server encountered an error. Please check the logs.';
                    errorHtml = '<p>' + errorMsg + '</p>';
                    console.error(xhr.responseText);
                }

                // Populate and show the error modal
                $('#danger-alert-modal .modal-body h4').text(modalTitle);
                $('#modal-error-list').html(errorHtml);
                var errorModal = new bootstrap.Modal(document.getElementById('danger-alert-modal'));
                errorModal.show();
            }
        });
    });
});

</script>

     
@endsection