@extends('lms.layout.layout')
@section('add-student-enrollment')

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Enrollment</a></li>
                        <li class="breadcrumb-item active">Add Enrollment</li>
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
                    <h4 class="header-title mb-3">Add Enrollment</h4>
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
                            <form class="needs-validation" id="EnrollmentForm" method="POST" action="{{ route('lms.store.student.enroll') }}" enctype="multipart/form-data" validate>
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        {{-- Select existing student --}}
                                        <div class="mb-3">
                                            <label class="form-label">Select Student</label>
                                            <select name="student_id" id="student_id" class="form-control" style="width: 100%;" ></select>
                                            @error('student_id')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Prefilled fields --}}
                                        <div class="mb-3">
                                            <label class="form-label">First Name</label>
                                            <input type="text" name="first_name" id="firstName" class="form-control" readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" id="studentEmail" class="form-control" readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Courses</label>
                                            <select class="form-select" name="course_id" id="courseSelect" required>
                                                <option value="">Select option</option>
                                                @foreach ($courses as $course)
                                                    <option value="{{ $course->id }}" data-fee="{{ $course->course_fee }}">
                                                        {{ $course->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                         {{-- Transaction ID --}}
                                        <div class="mb-3 transaction-id d-none">
                                            <label for="transaction_id" class="form-label">Transaction ID</label>
                                            <input type="text" name="transaction_id" class="form-control" placeholder="Leave blank to auto-generate">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" name="last_name" id="lastName" class="form-control" readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Phone Number</label>
                                            <input type="text" name="phone" id="studentPhone" class="form-control" readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select name="status" class="form-select enrollment-status">
                                                <option value="">Choose a Status</option>
                                                @foreach(\App\Models\StudentEnrollment::getStatusOptions() as $status)
                                                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- Payment Options Section (hidden by default) --}}
                                        <div class="mb-3 payment-options d-none">
                                            <label for="payment_method" class="form-label">Payment Method</label>
                                            <select name="payment_method" class="form-select">
                                                <option value="">Choose a Payment Method</option>
                                                <option value="razorpay">Razorpay</option>
                                                <option value="gpay">GPay</option>
                                                <option value="bank_transfer">Bank Transfer</option>
                                                <option value="cash">Cash</option>
                                            </select>
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
            $('#EnrollmentForm').submit(function (e) {
                e.preventDefault();
                let formData = new FormData(this);

                // Reset modal messages
                $('#modal-error-list').html('');
                $('#modal-success-message').html('');

                $.ajax({
                    type: 'POST',
                    url: "{{ route('lms.store.student.enroll') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#modal-success-message').text(response.message);
                        var successModal = new bootstrap.Modal(document.getElementById('success-alert-modal'));
                        successModal.show();
                        $('#EnrollmentForm')[0].reset();
                        setTimeout(() => {
                                    window.location.href = "{{ route('lms.students.enroll') }}";
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
                            var errorModal = new bootstrap.Modal(document.getElementById('danger-alert-modal'));
                            errorModal.show();
                        }
                    }
                });
            });
        });
    </script>
        @push('scripts')
        <script>
           $(document).ready(function () {
    $('#student_id').select2({
        placeholder: 'Search by name or admission number',
        minimumInputLength: 2,
        ajax: {
            url: '{{ url("cms/mobile/enrollments/students/search") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { search_term: params.term };
            },
            processResults: function (data, params) {
                let results = data.map(function (student) {
                    return {
                        id: student.id,
                        text: student.text,
                        first_name: student.first_name,
                        last_name: student.last_name,
                        email: student.email,
                        phone: student.phone
                    };
                });

                // If no results, offer to create a new student
                if (results.length === 0 && params.term) {
                    results.push({
                        id: 'new',
                        text: '➕ Create new student: ' + params.term,
                        is_new: true,
                        typed_name: params.term
                    });
                }

                return { results: results };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    });

    // Prefill fields
    $('#student_id').on('select2:select', function (e) {
        var data = e.params.data;

        if (data.is_new) {
            // Allow manual entry for new student
            $('#firstName').val(data.typed_name).prop('readonly', false);
            $('#lastName').val('').prop('readonly', false);
            $('#studentEmail').val('').prop('readonly', false);
            $('#studentPhone').val('').prop('readonly', false);

            // Instead of clearing student_id, set a hidden flag
            $('<input>').attr({
                type: 'hidden',
                id: 'is_new_student',
                name: 'is_new_student',
                value: '1'
            }).appendTo('#EnrollmentForm');
            
            // Clear select value (so validation doesn’t expect student_id)
            $('#student_id').val(null).trigger('change');
        } else {
            // Existing student → prefill readonly
            $('#firstName').val(data.first_name).prop('readonly', true);
            $('#lastName').val(data.last_name).prop('readonly', true);
            $('#studentEmail').val(data.email).prop('readonly', true);
            $('#studentPhone').val(data.phone).prop('readonly', true);

            // Remove hidden flag if exists
            $('#is_new_student').remove();
        }
    });

});
document.addEventListener("DOMContentLoaded", function () {
    const statusSelect = document.querySelector(".enrollment-status");
    const paymentOptions = document.querySelector(".payment-options");
    const transactionIdField = document.querySelector(".transaction-id");

    if (statusSelect) {
        statusSelect.addEventListener("change", function () {
            if (this.value.toLowerCase() === "enrolled") {
                paymentOptions.classList.remove("d-none");
                transactionIdField.classList.remove("d-none");
            } else {
                paymentOptions.classList.add("d-none");
                transactionIdField.classList.add("d-none");
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const courseSelect = document.getElementById("courseSelect");
    const statusSelect = document.querySelector(".enrollment-status");

    if (courseSelect && statusSelect) {
        courseSelect.addEventListener("change", function () {
            const selectedOption = this.options[this.selectedIndex];
            const courseFee = parseFloat(selectedOption.getAttribute("data-fee")) || 0;

            if (courseFee === 0) {
                statusSelect.value = "free";

                // Add hidden input to force submission
                let hiddenStatus = document.getElementById("hiddenStatus");
                if (!hiddenStatus) {
                    hiddenStatus = document.createElement("input");
                    hiddenStatus.type = "hidden";
                    hiddenStatus.name = "status";
                    hiddenStatus.id = "hiddenStatus";
                    document.getElementById("EnrollmentForm").appendChild(hiddenStatus);
                }
                hiddenStatus.value = "free";

                statusSelect.setAttribute("disabled", "disabled"); // UI locked
            } else {
                // Paid course → remove hidden input
                let hiddenStatus = document.getElementById("hiddenStatus");
                if (hiddenStatus) hiddenStatus.remove();

                statusSelect.removeAttribute("disabled");
                statusSelect.value = "";
            }
        });
    }
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const courseSelect = document.getElementById("courseSelect");
    const statusSelect = document.querySelector(".enrollment-status");

    courseSelect.addEventListener("change", function () {
        const selectedOption = courseSelect.options[courseSelect.selectedIndex];
        const courseFee = parseFloat(selectedOption.getAttribute("data-fee")) || 0;

        // Loop through status options
        Array.from(statusSelect.options).forEach(option => {
            if (option.value === "free") {
                if (courseFee > 0) {
                    option.style.display = "none"; // hide free if course has fee
                    if (statusSelect.value === "free") {
                        statusSelect.value = ""; // reset if currently selected
                    }
                } else {
                    option.style.display = "block"; // show free if course is free
                }
            }
        });
    });
});
</script>
<style>
    .select2-container--default .select2-selection--single {
    height: 40px !important;
    line-height: 50px !important;
}

/* Adjust text alignment */
.select2-container--default .select2-selection__rendered {
    line-height: 40px !important;
}

/* Adjust the dropdown arrow */
.select2-container--default .select2-selection__arrow {
    height: 40px !important;
}
</style>
@endpush

@endsection
