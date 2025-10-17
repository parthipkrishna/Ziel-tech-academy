@extends('lms.layout.layout')
@section('add-top-achiever')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Top Achiever</a></li>
                        <li class="breadcrumb-item active">Add Top Achiever</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-3">Add Top Achiever</h4>
                    <div class="row justify-content-center">
                        @if ($message = session()->get('message'))
                            <div class="alert alert-success text-center w-75">
                                <h6 class="text-center fw-bold">{{ $message }}...</h6>
                            </div>
                        @endif
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
                            <form class="needs-validation" id="TopAchieverForm" method="POST" action="{{ route('lms.store.top.achiever') }}" enctype="multipart/form-data"  validate>
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">

                                        <div class="mb-3">
                                            <label for="student_id" class="form-label">Student</label>
                                            <select class="form-control" id="student_id" name="student_id" style="width: 100%" required></select>
                                            @error('student_id')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="name_display">Name</label>
                                            <input type="text" id="name_display" class="form-control" placeholder="Name" disabled>
                                            <input type="hidden" name="name" id="name" value="{{ old('name') }}">
                                            @error('name')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="district" class="form-label">Course</label>
                                            <select class="form-select" id="example-select" name="course_id" required>
                                                <option value="">Select option</option>
                                                <!-- Dynamic options will be added here -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Upload Image</label>
                                            <input type="file" name="image" class="form-control" required >
                                            @error('image')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <input type="hidden" name="status" value="0">
                                            <label for="status" class="form-label">Status: </label></br/>
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
                        </div> <!-- end preview-->
                    </div> <!-- end tab-content-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

@push('scripts')
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Add custom image extension validator
        $.validator.addMethod("imageExtension", function(value, element) {
            return this.optional(element) || /\.(jpe?g|png|gif|webp)$/i.test(value);
        }, "Please upload a valid image (jpg, jpeg, png, gif, webp).");

        // Init Select2
        $('#student_id').select2({
            placeholder: 'Search by name or admission number',
            minimumInputLength: 2,
            ajax: {
                url: '{{ url("cms/mobile/top-achievers/students/search") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { search_term: params.term };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function (student) {
                            return {
                                id: student.id,
                                text: student.text,
                                full_name: student.student_name,
                                courses: student.courses
                            };
                        })
                    };
                },
                cache: true
            }
        });

       $('#student_id').on('select2:select', function (e) {
        var selected = e.params.data;
        $('#name_display').val(selected.full_name || selected.student_name);
        $('#name').val(selected.full_name || selected.student_name).valid();
        
        // Update course dropdown
        var $courseSelect = $('#example-select');
        $courseSelect.empty().append('<option value="">Select option</option>');
        
        if (selected.courses && Object.keys(selected.courses).length > 0) {
            $.each(selected.courses, function(id, name) {
                $courseSelect.append($('<option>', {
                    value: id,
                    text: name
                }));
            });
        } else {
            $courseSelect.append($('<option>', {
                value: '',
                text: 'No subscribed courses found',
                disabled: true
            }));
        }
        
        $(this).valid();
    });

        // jQuery Validate setup
        $('#TopAchieverForm').validate({
            ignore: '', // don't ignore any fields (including hidden)
            rules: {
                student_id: { required: true },
                name: { required: true },
                image: {
                    required: true,
                    imageExtension: true,
                    maxFileSize: 2 * 1024 * 1024
                }
            },
            messages: {
                student_id: { required: "Please select a student." },
                name: { required: "Name is required." },
                image: {
                    required: "Please upload an image.",
                    imageExtension: "Please upload a valid image (jpg, jpeg, png, gif, webp).",
                    maxFileSize: "File must be less than 2 MB."
                }
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('text-danger');
                if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('span.select2'));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            }
        });

        // Trigger validation on file change
        $('input[type="file"][name="image"]').on('change', function () {
            $(this).valid();
        });
    });
//validation #BannersForm
$(document).ready(function () {
        $('#TopAchieverForm').submit(function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            // clear previous errors
            $('#modal-error-list').html('');
            $('#modal-success-message').html('');
            $('.is-invalid').removeClass('is-invalid');

            $.ajax({
                type: 'POST',
                url: "{{ route('lms.store.top.achiever') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    $('#modal-success-message').text(response.message ?? 'Top Achiever created successfully!');
                    let successModal = new bootstrap.Modal(document.getElementById('success-alert-modal'));
                    successModal.show();
                    $('#TopAchieverForm')[0].reset();
                    setTimeout(() => {
                                        window.location.href = "{{ route('lms.top.achievers') }}";
                                    }, 1500);
                },
                error: function (xhr) {
                    let errorHtml = '';
                    let modalTitle = 'An Unexpected Error Occurred!';

                    if (xhr.status !== 422) { 
                        let errorMsg = xhr.responseJSON?.error || xhr.responseJSON?.message || 'Something went wrong. Please check the logs.';
                        errorHtml = '<p>' + errorMsg + '</p>';
                        console.error(xhr.responseText);
                        $('#danger-alert-modal .modal-body h4').text(modalTitle);
                        $('#modal-error-list').html(errorHtml);
                        let errorModal = new bootstrap.Modal(document.getElementById('danger-alert-modal'));
                        errorModal.show();
                    }
                }
        });
    });
});
</script>
@endpush
@endsection

