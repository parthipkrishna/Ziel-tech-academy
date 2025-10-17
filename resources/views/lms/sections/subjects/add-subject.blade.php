@extends('lms.layout.layout')
@section('add-subjects')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Subjects</a></li>
                        <li class="breadcrumb-item active">Add Subject</li>
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
                    <h4 class="header-title mb-3">Add Subject</h4>
                    <div class="row justify-content-center">
                         {{-- Display general messages --}}
                         @if ($message = session()->get('message'))
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
                            <form class="needs-validation" id="SubjectsForm" method="POST" action="{{ route('lms.store.subject') }}" enctype="multipart/form-data"  validate>
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom01">Name</label>
                                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="name" placeholder="Name" required>
                                            @error('name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="validationCustom01">Total Hours</label>
                                            <input type="text" name="total_hours" value="{{ old('total_hours') }}" class="form-control" id="validationCustom01" placeholder="Total Hours" required>
                                            @error('total_hours')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="district" class="form-label">Courses</label>
                                            <select class="form-select" id="example-select" name="course_id" required>
                                                <option value="">Select option</option>
                                                @foreach ($courses as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="web_thumbnail" class="form-label">Upload Web Thumbnail</label>
                                            <input type="file" name="web_thumbnail" class="form-control" >
                                        </div>

                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status: </label></br/>
                                            <input  type="checkbox" name="status"  id="switch3"  value="1"  checked  data-switch="success" onchange="this.value = this.checked ? 1 : 0;" />
                                            <label for="switch3" data-on-label="" data-off-label=""></label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="example-textarea" class="form-label">Short Description</label>
                                            <textarea class="form-control" name="short_desc" id="short_desc" rows="3" required></textarea>
                                            @error('short_desc')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="example-textarea" class="form-label">Description</label>
                                            <textarea class="form-control" name="desc" id="desc" rows="3" required></textarea>
                                            @error('desc')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="mobile_thumbnail" class="form-label">Upload Mobile Thumbnail</label>
                                            <input type="file" name="mobile_thumbnail" class="form-control" >
                                        </div>
                                    </div>
                                </div>
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
        $(document).ready(function () {
            $('#SubjectsForm').submit(function (e) {
                e.preventDefault();
                let formData = new FormData(this);

                // clear previous errors/messages
                $('#modal-error-list').html('');
                $('#modal-success-message').html('');

                $.ajax({
                    type: 'POST',
                    url: "{{ route('lms.store.subject') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#modal-success-message').text(response.message);
                        let successModal = new bootstrap.Modal(document.getElementById('success-alert-modal'));
                        successModal.show();
                        $('#SubjectsForm')[0].reset();
                        setTimeout(() => {
                                    window.location.href = "{{ route('lms.subjects') }}";
                                }, 1500);
                            },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorHtml = '<ul class="list-unstyled text-start">';
                            $.each(errors, function (field, messages) {
                                errorHtml += '<li>' + messages[0] + '</li>';
                                // highlight invalid fields
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

@endsection
