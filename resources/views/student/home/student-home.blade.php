@extends('student.layouts.layout')
@section('student-dashboard')

<div class="content pt-3 px-2">
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row">
            <div class="card card-h-100 border-none"
                style="padding: 20px 30px 40px 30px;border-radius: 20px; background: var(--Congress-Blue-50, #ffffff);">
                <div class="row slider-parent-div">
                    <!-- Text Content -->
                    <div class="col-xl-6 col-lg-6">
                        @if(!empty($data['banner']) && $data['banner']->isNotEmpty())
                            <div class="slider-text-container">
                            @foreach($data['banner'] as $banner)
                                    <div class="slider-text {{ $loop->first ? 'active' : '' }}">
                                        <h3>
                                            @if($banner->type === 'toolkit' && $banner->toolkit)
                                                {{ $banner->toolkit->name }}
                                            @elseif($banner->type === 'course' && $banner->course)
                                                {{ $banner->course->name }}
                                            @else
                                                {{ $banner->title ?? '' }}
                                            @endif
                                        </h3>
                                        <p>
                                            {{ $banner->short_description ?? '' }}
                                        </p>
                                        <div class="mt-4 d-flex gap-3">
                                            @if($banner->type === 'course' && $banner->course)
                                                @if($banner->course->is_subscribed)
                                                    {{-- Already Subscribed --}}
                                                    <a href="{{ route('student.portal.subjects', $banner->related_id) }}" class="btn-enroll-link">
                                                        <button class="btn-enroll">Go to Course</button>
                                                    </a>
                                                @else
                                                    {{-- Not Subscribed --}}
                                                    <a href="{{ route('student.portal.courses.show', $banner->related_id) }}" class="btn-enroll-link">
                                                        <button class="btn-enroll">Enroll Now</button>
                                                    </a> 
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
                                @endforeach
                            </div>
                        @else
                            <div class="text-center p-5">
                                <h4>No banners found</h4>
                            </div>
                        @endif
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
                                            <img class="d-block img-fluid" src="{{ asset('storage/' . $banner->image) }}">
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
                                <div class="text-center p-5">
                                    <h4>No banners found</h4>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- end row -->
        <div class="row mt-3">
            <div class="card card-h-100 shadow-none subject-section"
                style="padding: 20px 30px 20px 30px;border: 1px solid #DDDDDD;border-radius: 20px;">
                <h3>Subjects</h3>
                <div class="row">
                    @forelse($data['subjects'] as $subject)
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 mt-3">
                            <a href="{{ route('student.portal.subjects') }}" style="text-decoration: none; color: inherit;">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="icon-bg">
                                            <i class="ri-book-line"></i>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <h6>{{ $subject->name }}</h6>
                                            <p>{{ $subject->completed_items }}/{{ $subject->total_items }}</p>
                                        </div>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-success"
                                                role="progressbar"
                                                style="width: {{ $subject->progress_percent }}%"
                                                aria-valuenow="{{ $subject->progress_percent }}"
                                                aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-12 text-center my-4">
                            <h5>No subjects available right now.</h5>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="card card-h-100 border-none"
                style="padding: 20px 30px 40px 30px; background: var(--Congress-Blue-50, #ffffff);border-radius: 20px;">
                <div class="row live-section">
                    <!-- Text Content -->   
                    @if(!empty($data['live_class']))
                         <div class="col-xl-6 col-lg-6">
                            <h6>
                                <span>LIVE
                                    <i class="ri-wireless-charging-line ms-1 me-1"></i>
                                </span>
                                Classes
                            </h6>

                            <h3>{{ $data['live_class']->name }}</h3>

                            <p style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                                {{ $data['live_class']->short_summary ?? 'No summary available.' }}
                            </p>

                            <div class="avatar-group mt-3">
                                <div class="avatar">
                                    <img class="img-fluid"
                                        src="{{ asset('student/assets/images/small/avatar-img-1.webp') }}"
                                        alt="User 1">
                                </div>
                                <div class="avatar">
                                    <img class="img-fluid"
                                        src="{{ asset('student/assets/images/small/avatar-img-2.webp') }}"
                                        alt="User 2">
                                </div>
                                <div class="avatar">
                                    <img class="img-fluid"
                                        src="{{ asset('student/assets/images/small/avatar-img-3.webp') }}"
                                        alt="User 3">
                                </div>
                                <div class="avatar">
                                    <img class="img-fluid"
                                        src="{{ asset('student/assets/images/small/avatar-img-4.webp') }}"
                                        alt="User 4">
                                </div>
                                <div class="avatar more-count">{{ $data['live_class']->participants->count() }}+</div>
                            </div>

                            <button class="mt-3" id="openPopup">
                                <i class="ri-notification-3-fill me-2"></i>
                                Join now
                            </button>
                            
                        <div class="overlay" id="overlay"></div>

                        <div class="popup" id="popup">
                            <div id="carouselExampleDark" class="carousel carousel-dark slide">
                                <div class="carousel-indicators">
                                    <button type="button" data-bs-target="#carouselExampleDark"
                                        data-bs-slide-to="0" class="active" aria-current="true"
                                        aria-label="Slide 1"></button>
                                    <button type="button" data-bs-target="#carouselExampleDark"
                                        data-bs-slide-to="1" aria-label="Slide 2"></button>
                                    <button type="button" data-bs-target="#carouselExampleDark"
                                        data-bs-slide-to="2" aria-label="Slide 3"></button>
                                </div>
                                <div id="liveClassCarousel" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        @foreach($data['all_live_classes'] as $index => $class)
                                            <div class="carousel-item px-3 {{ $index === 0 ? 'active' : '' }}">
                                                <img  src="{{ !empty($data['live_class']->thumbnail_image) ? asset('storage/' . $data['live_class']->thumbnail_image) : asset('student/assets/images/small/liveClass-img.webp') }}" class="img-fluid" alt="Live Class" style="width: 100%; height: auto; object-fit: contain;">
                                                <h6>
                                                    <span>LIVE <i class="ri-wireless-charging-line ms-1 me-1"></i></span> Classes
                                                </h6>
                                                <h3 class="mt-2">{{ $class->name }}</h3>
                                                <div class="d-flex align-items-center mt-3">
                                                    <img src="{{ optional($class->tutor->user)->profile_image ? asset('storage/' . $class->tutor->user->profile_image) : asset('student/assets/images/small/avatar-img-1.webp') }}"
                                                        class="rounded-circle me-3" alt="Tutor Image" style="width: 50px; height: 50px; object-fit: cover;">
                                                    
                                                    <div>
                                                        <p class="mb-0 fw-bold">{{ optional($class->tutor->user)->name ?? 'Unknown' }}</p>
                                                        <small class="text-muted">Tutor</small>
                                                    </div>
                                                </div>
                                                <p style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                                                    {{ $class->short_summary ?? 'No summary available.' }}
                                                </p>
                                                <div class="timeCalender">
                                                    @if(!empty($class->start_time) && !empty($class->end_time))
                                                        <span>
                                                            <i class="ri-calendar-line me-1"></i>
                                                            {{ \Carbon\Carbon::parse($class->start_time)->format('M d, Y') }}
                                                            <i class="ri-time-line me-1 ms-1"></i>
                                                            {{ \Carbon\Carbon::parse($class->start_time)->format('h:i A') }} -
                                                            {{ \Carbon\Carbon::parse($class->end_time)->format('h:i A') }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">Date and time not available</span>
                                                    @endif
                                                </div>
                                                <div class="d-flex align-items-center mt-3">
                                                    <i class="fab fa-google text-danger fs-4 me-2"></i>
                                                    <span>Google Meet</span>
                                                </div>
                                                <div class="avatar-group mt-3">
                                                    @foreach($class->participants->take(4) as $participant)
                                                        <div class="avatar-group mt-3">
                                                            <div class="avatar">
                                                                <img class="img-fluid"
                                                                    src="{{ asset('student/assets/images/small/avatar-img-1.webp') }}"
                                                                    alt="User 1">
                                                            </div>
                                                            <div class="avatar">
                                                                <img class="img-fluid"
                                                                    src="{{ asset('student/assets/images/small/avatar-img-2.webp') }}"
                                                                    alt="User 2">
                                                            </div>
                                                            <div class="avatar">
                                                                <img class="img-fluid"
                                                                    src="{{ asset('student/assets/images/small/avatar-img-3.webp') }}"
                                                                    alt="User 3">
                                                            </div>
                                                            <div class="avatar">
                                                                <img class="img-fluid"
                                                                    src="{{ asset('student/assets/images/small/avatar-img-4.webp') }}"
                                                                    alt="User 4">
                                                            </div>
                                                            <div class="avatar more-count">{{ $class->participants->count() }}+</div>
                                                        </div>

                                                    @endforeach
                                                    <div class="avatar more-count">{{ $class->participants->count() }}+</div>
                                                </div>

                                                <button class="btn btn-primary mt-3" onclick="window.open('{{ route('live-class.join', $class->id) }}', '_blank')">Join now</button>
                                                 <!-- Need Help Section -->
                                                <div class="p-3 bg-light rounded">
                                                    <h6>Need Help?</h6>
                                                    <div class="list-group">
                                                        <a href="https://wa.me/1234567890" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center">
                                                            <i class="ri-whatsapp-line text-success fs-5 me-3"></i> WhatsApp
                                                            <i class="ri-arrow-right-s-line ms-auto"></i>
                                                        </a>
                                                        <a href="mailto:support@zieltech.com" class="list-group-item list-group-item-action d-flex align-items-center">
                                                            <i class="ri-mail-line text-danger fs-5 me-3"></i> Email
                                                            <i class="ri-arrow-right-s-line ms-auto"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#liveClassCarousel" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#liveClassCarousel" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>

                                <button class="carousel-control-prev" type="button"
                                    data-bs-target="#carouselExampleDark" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button"
                                    data-bs-target="#carouselExampleDark" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                        <span id="closePopup"></span>
                    </div>
                    <!-- Carousel -->
                    <div class="col-xl-6 col-lg-6">
                        <img class="img-fluid"  src="{{ !empty($data['live_class']->thumbnail_image) ? asset('storage/' . $data['live_class']->thumbnail_image) : asset('student/assets/images/small/liveClass-img.webp') }}" alt="">
                    </div>
                   @else
                    <div class="col-12 d-flex justify-content-center align-items-center" style="min-height: 250px;">
                        <p class="m-0">No live class available right now.</p>
                    </div>
                @endif
                </div>
            </div>
        </div>
        <div class="row history-section mt-3" style="padding: 20px 0px 40px 0px;">
            <h4>Last Watched Video</h4>
                <div class="container-fluid">
                    <div class="row">
                        @if($data['videoHistory'] && $data['videoHistory']->video)
                            @php $video = $data['videoHistory']->video; @endphp

                            <div class="col-lg-3 col-md-6 mt-3">
                                <div class="card">
                                    <div class="corner"></div>
                                    <div class="corner-bottom"></div>
                                    <img src="{{ !empty($video->thumbnail) ? asset('storage/' . $video->thumbnail) : asset('student/assets/images/small/person-img.webp') }}"
                                        alt="{{ $video->title ?? 'Video' }}"
                                        class="person-image">

                                    <div class="title">
                                        <i class="ri-play-line"></i>
                                        {{ \Illuminate\Support\Str::limit($video->title ?? 'Untitled Video', 30, '...') }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-12">
                                <p class="text-muted text-center">No watch history available.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- content -->
          
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