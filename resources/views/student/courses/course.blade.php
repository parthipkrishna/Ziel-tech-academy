@extends('student.layouts.layout')
@section('student-courses')

<div class="content pt-3 px-2">
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row">
            <div class="card card-h-100 border-none"
                style="padding: 20px 30px 40px 30px;border-radius: 20px; background: var(--Congress-Blue-50, #ffffff);">
                <div class="row slider-parent-div">
                    <!-- Text Content -->
                    <div class="col-xl-6 col-lg-6">
                        <div class="slider-text-container">
                            @forelse($data['banner'] as $banner)
                                <div class="slider-text {{ $loop->first ? 'active' : '' }}">
                                    <h3>
                                        @if($banner->type === 'toolkit' && $banner->toolkit)
                                            {{ $banner->toolkit->name }}
                                        @elseif($banner->type === 'course' && $banner->course)
                                            {{ $banner->course->name }}
                                        @else
                                            Unlock the Future of Smartphone Tech
                                        @endif
                                    </h3>
                                    <p>
                                        {{ $banner->short_description ??
                                            'Experience cutting-edge innovation with the latest advancements in smartphone technology. Stay ahead with smarter, faster, and more powerful mobile experiences.'
                                        }}
                                    </p>
                                    <div class="mt-4 d-flex gap-3">
                                        @if($banner->type === 'course' && $banner->course)
                                            @if($banner->course->is_subscribed)
                                                {{-- Already Subscribed --}}
                                                <a href="{{ route('student.portal.courses.show', $banner->course->id) }}#course-details" 
                                                class="know-btn-link">
                                                    <button class="know-btn">Know More</button>
                                                </a>
                                            @else
                                                {{-- Not Subscribed --}}
                                                <button class="btn-enroll" data-bs-toggle="modal" data-bs-target="#enrollModal{{ $banner->course->id }}">
                                                    Enroll Now
                                                </button>
                                                    {{-- Modal --}}
                                                    <div class="modal fade" id="enrollModal{{ $banner->course->id }}" tabindex="-1" aria-labelledby="enrollModalLabel{{ $banner->course->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title" id="enrollModalLabel{{ $banner->course->id }}">{{ $banner->course->name }}</h4>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form method="POST" action="{{ route('payments-student.checkout') }}" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="course_id" value="{{ $banner->course->id }}">
                                                                        
                                                                        <p><strong>Duration:</strong> {{ $banner->course->total_hours ? $banner->course->total_hours . ' Hours' : 'N/A' }}</p>
                                                                        <p><strong>Start Date:</strong> {{ \Carbon\Carbon::today()->format('d M Y') }}</p>
                                                                        <p><strong>End Date:</strong> 
                                                                            {{ $banner->course->course_end_date 
                                                                                ? \Carbon\Carbon::today()->addMonths((int)$banner->course->course_end_date)->format('d M Y') 
                                                                                : 'N/A' }}
                                                                        </p>
                                                                        <p><strong>Course Fee:</strong> ₹{{ number_format($banner->course->course_fee ?? 0, 2) }}</p>

                                                                        @if($banner->course->toolkits && $banner->course->toolkits->isNotEmpty())
                                                                            <hr>
                                                                            <h5>Add Toolkit</h5>
                                                                            @foreach($banner->course->toolkits as $toolkit)
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input" type="checkbox" 
                                                                                        name="toolkit_id" value="{{ $toolkit->id }}" 
                                                                                        id="toolkit{{ $toolkit->id }}_{{ $banner->course->id }}">
                                                                                    <label class="form-check-label" for="toolkit{{ $toolkit->id }}_{{ $banner->course->id }}">
                                                                                        {{ $toolkit->name }} - ₹{{ number_format($toolkit->price, 2) }}
                                                                                    </label>
                                                                                    <a href="javascript:void(0);" 
                                                                                    onclick="confirmEnquiry({{ $toolkit->id }})" 
                                                                                    class="ms-2 text-primary" 
                                                                                    style="font-size: 14px;">
                                                                                        View Details
                                                                                    </a>
                                                                                </div>
                                                                                <input type="hidden" name="course_id_for_modal" value="{{ $banner->course->id }}">
                                                                               <div class="mt-3">
                                                                                    <label for="referral_code_{{ $banner->course->id }}" class="form-label">Referral Code (optional)</label>
                                                                                    <input type="text"
                                                                                        name="referral_code"
                                                                                        id="referral_code_{{ $banner->course->id }}"
                                                                                        class="form-control @error('referral_code') is-invalid @enderror"
                                                                                        placeholder="Enter referral code"
                                                                                        value="{{ old('referral_code') }}">

                                                                                    @error('referral_code')
                                                                                        <div class="invalid-feedback d-block">
                                                                                            {{ $message }}
                                                                                        </div>
                                                                                    @enderror
                                                                                </div>
                                                                            @endforeach
                                                                        @else
                                                                            <div class="mt-3">
                                                                                <label for="referral_code" class="form-label">Referral Code (optional)</label>
                                                                                <input type="text" name="referral_code" class="form-control" placeholder="Enter referral code">
                                                                            </div>
                                                                        @endif

                                                                        <div class="form-check mt-3">
                                                                            <input class="form-check-input" type="checkbox" name="use_loyalty_points" 
                                                                                id="use_loyalty_points_{{ $banner->course->id }}" value="1">
                                                                            <label class="form-check-label" for="use_loyalty_points_{{ $banner->course->id }}">
                                                                                Use my loyalty points 
                                                                            </label>
                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="submit" class="btn-enroll">
                                                                            Proceed to Checkout
                                                                        </button>
                                                                        <button type="button" class="know-btn" data-bs-dismiss="modal">Close</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            <a href="{{ route('student.portal.courses.show', $banner->related_id) }}" class="know-btn-link">
                                                <button class="know-btn">Know More</button>
                                            </a>
                                        @elseif($banner->type === 'toolkit' && $banner->toolkit)
                                            <a href="#" class="btn-enroll-link">
                                                <button class="btn-enroll" onclick="confirmEnquiry({{ $banner->related_id }})">Enquiry Now</button>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="slider-text active">
                                    <h3>No banners found</h3>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <!-- Carousel -->
                    <div class="col-xl-6 col-lg-6">
                        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                            @if(!empty($data['banner']) && $data['banner']->isNotEmpty())
                                <ol class="carousel-indicators">
                                    @foreach($data['banner'] as $key => $banner)
                                        <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $key }}" class="{{ $loop->first ? 'active' : '' }}"></li>
                                    @endforeach
                                </ol>
                                <div class="carousel-inner">
                                    @foreach($data['banner'] as $banner)
                                        <div class="carousel-item {{ $loop->first ? 'active' : '' }} px-3">
                                            <img class="d-block img-fluid" src="{{ !empty($banner->image) ? asset('storage/' . $banner->image) : asset('student/assets/images/small/slider-img.webp') }}">
                                        </div>
                                    @endforeach
                                </div>
                                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </a>
                            @else
                                <div class="text-center py-5">
                                    <h5>No banners found</h5>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row course-details"  id="course-details">
            <div class="col-lg-8 m-0 p-0">
                <div class="card">
                    <div class="card-body">
                        <h4>Course Structure</h4>
                        <div class="row gap-3 mt-3">
                            <div class="col-lg-6 course-details-card">
                                <h6><i class="me-2 ri-wireless-charging-line"></i>Live Sessions</h6>
                            </div>
                            <div class="col-lg-6 course-details-card">
                                <h6><i class="me-2 ri-database-line"></i>Certification</h6>
                            </div>
                            <div class="col-lg-6 course-details-card">
                                <h6><i class="me-2 ri-git-repository-fill"></i>Assignments</h6>
                            </div>
                            <div class="col-lg-6 course-details-card">
                                <h6><i class="me-2  ri-router-line"></i>Technical Assistance</h6>
                            </div>
                            <div class="col-lg-6 course-details-card">
                                <h6><i class="me-2 ri-scan-fill"></i>Quality Checking</h6>
                            </div>
                            <div class="col-lg-6 course-details-card">
                                <h6><i class="me-2 ri-task-fill"></i>Job Placement Guarantee</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 syllabus-datails">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <h3>Syllabus</h3>
                            <p>What you will learn in this course</p>
                        </div>

                        @if(isset($course))
                            {{-- Case 1: Displaying a SPECIFIC course's syllabus --}}
                            @forelse ($course->subjects as $subject)
                                <div class="mt-4">
                                    <h3>{{ $subject->name }}</h3>
                                    <p><i class="ri-router-line me-2"></i>{{ $subject->videos_count ?? '0' }} videos</p>
                                </div>
                            @empty
                                <div class="mt-4">
                                    <p>No syllabus has been added to this course yet.</p>
                                </div>
                            @endforelse

                        @else
                            {{-- Case 2: Default view, showing syllabus for SUBSCRIBED courses --}}
                            @forelse ($subscriptions as $subscription)
                                @foreach ($subscription->course->subjects as $subject)
                                    <div class="mt-4">
                                        <h3>{{ $subject->name }}</h3>
                                        <p><i class="ri-router-line me-2"></i>{{ $subject->videos_count ?? '0' }} videos</p>
                                    </div>
                                @endforeach
                            @empty
                                <div class="mt-4">
                                    <p>Your subscribed course syllabus will appear here.</p>
                                </div>
                            @endforelse
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- container -->
</div>

@if ($errors->any() && old('course_id_for_modal'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const courseId = '{{ old('course_id_for_modal') }}';

            if (courseId) {
                const modalId = 'enrollModal' + courseId;
                const modalElement = document.getElementById(modalId);

                if (modalElement) {
                    const errorModal = new bootstrap.Modal(modalElement);
                    errorModal.show();
                }
            }
        });
    </script>
@endif
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const carouselEl = document.getElementById('carouselExampleIndicators');
        const carousel = new bootstrap.Carousel(carouselEl, {
            interval: 5000, // adjust your interval
            ride: 'carousel'
        });

        // Update slider-text active class on slide
        carouselEl.addEventListener('slid.bs.carousel', function (event) {
            const currentIndex = event.to;
            const textBlocks = document.querySelectorAll('.slider-text');

            textBlocks.forEach((block, index) => {
                block.classList.toggle('active', index === currentIndex);
            });
        });

        // Stop carousel when any modal opens, resume when closes
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.addEventListener('show.bs.modal', () => {
                carousel.pause();
            });
            modal.addEventListener('hidden.bs.modal', () => {
                carousel.cycle();
            });
        });

        // Swipe script
        let isDown = false;
        let startX;

        carouselEl.addEventListener("mousedown", (e) => {
            isDown = true;
            startX = e.pageX;
            e.preventDefault();
        });

        carouselEl.addEventListener("mouseup", () => { isDown = false; });
        carouselEl.addEventListener("mouseleave", () => { isDown = false; });

        carouselEl.addEventListener("mousemove", (e) => {
            if (!isDown) return;
            const difference = e.pageX - startX;

            if (difference > 50) {
                carousel.prev();
                isDown = false;
            } else if (difference < -50) {
                carousel.next();
                isDown = false;
            }
        });
    });

    // popUp code
    const openPopup = document.getElementById("openPopup");
    const closePopup = document.getElementById("closePopup");
    const popup = document.getElementById("popup");
    const overlay = document.getElementById("overlay");

    openPopup.addEventListener("click", () => {
        popup.style.display = "block";
        overlay.style.display = "block";
    });

    closePopup.addEventListener("click", () => {
        popup.style.display = "none";
        overlay.style.display = "none";
    });

    overlay.addEventListener("click", () => {
        popup.style.display = "none";
        overlay.style.display = "none";
    });

    const knowMoreBtn = document.getElementById('know-more-btn');
    knowMoreBtn.addEventListener('click', function() {
        const redirectUrl = '{{ route('student.portal.courses') }}' + '#course-details';
        
        window.location.href = redirectUrl;
    });
</script>
@push('scripts')
<script>
    function confirmEnquiry(toolkitId) {
        fetch("{{ route('student.toolkit.details', '') }}/" + toolkitId, {
            method: "GET",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            }
        })
        .then(response => response.json())
        .then(toolkit => {
            if (!toolkit) {
                Swal.fire('Error!', 'Toolkit details not found.', 'error');
                return;
            }

            const mediaHtml = (toolkit.media && toolkit.media.length) 
                ? '<div style="display:flex; gap:10px; margin-top:10px;">' +
                toolkit.media.map(m => `<img src="${m.file_path}" style="width:130px; height:130px; object-fit:cover; border-radius:6px;">`).join('') +
                '</div>'
                : '';

            Swal.fire({
                title: toolkit.name,
                html: `
                    <div class="card swal-card" style="border:1px solid #ddd; border-radius:20px; padding:20px; text-align:left;">      
                        <p>${toolkit.short_description ?? 'No description found'}</p>
                        <p>Price: <del>${toolkit.price ? '₹' + toolkit.price : 'No Price Available'}</del> 
                        <strong>${toolkit.offer_price ? '₹' + toolkit.offer_price : 'No Offer Price Available'}</strong></p>
                        ${mediaHtml}
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Yes, Enquiry Now',
                cancelButtonText: 'Cancel',
                width: '600px', 
                customClass: { 
                    title: 'swal2-title-sm', 
                    popup: 'custom-swal-popup' 
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('student.toolkit.enquiry') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({ toolkit_id: toolkitId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Success!', data.message, 'success');
                        } else {
                            Swal.fire('Error!', data.message, 'error');
                        }
                    })
                    .catch(() => {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    });
                }
            });
        })
        .catch(() => {
            Swal.fire('Error!', 'Unable to load toolkit details.', 'error');
        });
    }
    </script>
    @endpush
@endsection