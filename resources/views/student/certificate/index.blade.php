@extends('student.layouts.layout')
@section('student-mcq-assesment')
 <div class="container">
    <h3 class="mb-4">My Certificates</h3>
        @if($enrollments->isEmpty())
            <div id="noCertMsg" class="text-center fs-5 mt-4 p-4 bg-light rounded shadow-sm" style="color:#333;">
                🎓 You have no certificates generated yet.
            </div>
        @else
        <div id="certificatePreview" class="row mt-4 g-4">
            @foreach($enrollments as $enrollment)
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-lg border-0 h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h5 class="card-title fw-bold text-secondary mb-3">
                                {{ $enrollment->course->name }}
                            </h5>
                            <button type="button"
                                    class="btn w-100 rounded-pill generate-certificate"
                                    style="background-color: #01b45bff; color: #fff; border: none;"
                                    data-student="{{ $student->id }}"
                                    data-course="{{ $enrollment->course->id }}">
                                <i class="bi bi-award-fill me-2"></i> Generate & View Certificate
                            </button>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".generate-certificate").forEach(button => {
        button.addEventListener("click", function () {
            let studentId = this.getAttribute("data-student");
            let courseId = this.getAttribute("data-course");

           fetch(`/api/v1/certificates/${studentId}/${courseId}`, {
                headers: {
                    "Authorization": "Bearer {{ auth()->user()->createToken('student')->plainTextToken }}"
                }
            })

            .then(res => res.json())
            .then(data => {
                if (data.status && data.certificate_url) {
                    // ✅ Open certificate in new tab
                    window.open(data.certificate_url, "_blank");
                } else {
                    alert(data.message || "Something went wrong while generating certificate.");
                }
            })
            .catch(err => {
                console.error("Error generating certificate:", err);
                alert("Error generating certificate.");
            });
        });
    });
});
</script>
@endsection
