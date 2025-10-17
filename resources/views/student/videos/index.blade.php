@extends('student.layouts.layout')
@section('student-subjects')

<div class="content  pt-3 px-2">
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row subject-datas">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h3>Subjects</h3>
                        @if ($subjects->count())
                            @foreach ($subjects as $index => $subject)
                                <button class="mt-3 subject-btn {{ $index == 0 ? 'active' : '' }}" 
                                    data-target="subject-{{ $subject->id }}">
                                    {{ $subject->name }}
                                </button>
                            @endforeach
                        @else
                            <div class="mt-3 alert alert-warning">
                                No subjects available.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @foreach ($subjects as $index => $subject)
                <div class="col-lg-8 subject-img {{ $index != 0 ? 'd-none' : '' }}" data-target="subject-{{ $subject->id }}">
                    @php
                        $firstSession = $subject->subjectSessions->firstWhere('videos', '!=', collect());
                        $firstVideo = $firstSession ? $firstSession->videos->first() : null;
                    @endphp

                    @if ($firstVideo)
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="position-relative video-wrapper">
                                <video class="img-fluid w-100 rounded-top video-player" controls controlsList="nodownload" 
                                    data-video-id="{{ $firstVideo->id }}" data-subject-id="{{ $subject->id }}" 
                                    data-session-id="{{ $firstSession->id }}" preload="none"
                                    poster="{{ !empty($firstVideo->thumbnail) ? asset('storage/' . $firstVideo->thumbnail) : asset('student/assets/images/small/subject-class-video-img-1.webp') }}">
                                    <source src="{{ asset('storage/videos/' . basename($firstVideo->video)) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                                <div class="position-absolute top-50 start-50 translate-middle play-overlay">
                                    <i class="bi bi-play-circle-fill" style="font-size: 60px; color: rgba(255,255,255,0.7);"></i>
                                </div>
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-start justify-content-end pe-2 pt-2"
                                    style="pointer-events: none; color: rgba(255,255,255,0.6); font-size: 14px; z-index: 10;">
                                    ZielTech Academy
                                </div>
                            </div>
                            <div class="card-body px-0 py-0">
                                <h5 class="mb-2">{{ $firstVideo->title }}</h5>
                                <p class="text-black mb-0 ms-0">{{ Str::limit($firstVideo->description, 100) }}</p>
                            </div>
                        </div>
                    @else
                        <div class="card shadow-sm border-0 mb-4 col-lg-12">
                            <div class="card-body text-center">
                                <p class="text-muted mt-5">No videos found</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <!-- Content Section -->
        @foreach ($subjects as $index => $subject)
        <div class="row electronics-classes {{ $index != 0 ? 'd-none' : '' }}" id="subject-{{ $subject->id }}" style="border-radius: 20px; background-color: var(--Congress-Blue-50,#ffffff);">
            <h4 class="px-3 pt-3">{{ $subject->name }}</h4>
            <div class="row g-3 px-3 pb-3">
                @forelse ($subject->subjectSessions as $sessionIndex => $session)    
                    @if ($session->videos && $session->videos->count())
                        @foreach ($session->videos as $video)
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                @if ($video->is_locked)
                                    <div class="card h-100 text-center position-relative">
                                        <div class="card-body">
                                            <div class="assessment-thumbnail mb-3 position-relative">
                                                <img src="{{ !empty($video->thumbnail) ? asset('storage/' . $video->thumbnail) : asset('student/assets/images/small/subject-class-video-img-1.webp') }}" 
                                                    class="img-fluid video-thumbnail blur-image" 
                                                    alt="Video Thumbnail">

                                                <div class="card-overlay d-flex justify-content-center align-items-center">
                                                    <img src="{{ asset('student/assets/images/lock_img.png') }}" alt="Locked" style="width: 50px; height: 50px;">
                                                </div>
                                            </div>

                                            <div class="text-start mt-3">
                                                <h6 class="mb-0 text-uppercase">Session {{ $sessionIndex + 1 }}</h6>
                                                <h4 class="mb-0">{{ $video->title }}</h4>
                                                <p style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 0.85rem;" class="subject-desc">
                                                    {{ $video->description }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <a href="#" class="text-decoration-none d-block w-100 h-100" data-bs-toggle="modal" data-bs-target="#videoModal{{ $video->id }}">
                                        <div class="card h-100 text-center position-relative">
                                            <div class="card-body">
                                                <div class="assessment-thumbnail mb-3 position-relative">
                                                    <img src="{{ !empty($video->thumbnail) ? asset('storage/' . $video->thumbnail) : asset('student/assets/images/small/subject-class-video-img-1.webp') }}" 
                                                        class="img-fluid video-thumbnail" 
                                                        alt="Video Thumbnail">

                                                    <div class="card-overlay d-flex justify-content-center align-items-center">
                                                        <i class="bi bi-play-circle-fill" style="font-size: 50px; color: white;"></i>
                                                    </div>
                                                </div>

                                                <div class="text-start mt-3">
                                                    <h6 class="mb-1 text-uppercase">Session {{ $sessionIndex + 1 }}</h6>
                                                    <h4 class="mb-0">{{ $video->title }}</h4>
                                                    <p style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 0.85rem;" class="subject-desc">
                                                        {{ $video->description }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        <!-- Modal -->
                        <div class="modal fade" id="videoModal{{ $video->id }}" tabindex="-1" aria-labelledby="videoModalLabel{{ $video->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-body p-0 position-relative"> 
                                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 z-10" data-bs-dismiss="modal" aria-label="Close" style="z-index: 1051;"></button>
                                        <video class="w-100 video-player" controls controlsList="nodownload" data-video-id="{{ $video->id }}"
                                            data-subject-id="{{ $subject->id }}" data-session-id="{{ $session->id }}"
                                            poster="{{ !empty($video->thumbnail) ? asset('storage/' . $video->thumbnail) : '' }}">
                                            <source src="{{ asset('storage/videos/' . basename($video->video)) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-start justify-content-end pe-2 pt-2"
                                            style="pointer-events: none; color: rgba(255,255,255,0.6); font-size: 14px; z-index: 10;">
                                            ZielTech Academy
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                @if ($session->assessments && $session->assessments->count())
                    @foreach ($session->assessments as $assessment)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            @if (isset($assessment->is_locked) && $assessment->is_locked)
                                <div class="card h-100 text-center bg-light position-relative">
                                    <div class="card-body">
                                        <div class="assessment-thumbnail mb-3 position-relative">
                                            <img src="{{ asset('student/assets/images/exam_img.png') }}" 
                                                class="img-fluid video-thumbnail {{ $assessment->is_locked ? 'blur-image' : '' }}" alt="Assessment Image">

                                            @if ($assessment->is_locked)
                                                <div class="card-overlay d-flex justify-content-center align-items-center">
                                                    <img src="{{ asset('student/assets/images/lock_img.png') }}" alt="Locked" style="width: 50px; height: 50px;">
                                                </div>
                                            @endif
                                        </div>

                                        <div class="text-start">
                                            <h6 class="mb-1 text-black">Assessment {{ $loop->iteration }}</h6>
                                            <h5 class="mb-1 text-black">{{ $assessment->name }}</h5>
                                            <h6 class="mb-1 text-black">
                                                {{ $assessment->duration }} minutes | {{ $assessment->total_sessions }} sessions
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                @else
                                    <a href="{{ route('student.assessment.show', $assessment->id) }}" class="text-decoration-none">
                                        <div class="card h-100">
                                            <div class="card-body text-center">
                                                <div class="assessment-thumbnail mb-3">
                                                    <img src="{{ asset('student/assets/images/exam_img.png') }}" 
                                                        class="img-fluid video-thumbnail" alt="Assessment Image">
                                                </div>
                                            <div class="text-start">
                                                    <h6 class="mb-1 text-black">Assessment {{ $loop->iteration }}</h6>
                                                    <h5 class="mb-1 text-black">{{ $assessment->name }}</h5>
                                                    <h6 class="mb-1 text-black">
                                                        {{ $assessment->duration }} minutes | {{ $assessment->total_sessions }} sessions
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    @endif

                @empty
                    <div class="col-12">
                        <div class="alert alert-warning text-center">
                            No subject sessions available for this subject.
                        </div>
                    </div>
                @endforelse
                {{-- Final Exam Section --}}
                @if ($subject->final_exam)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        @if ($subject->final_exam->is_locked)
                            <div class="card h-100 text-center bg-light position-relative">
                                <div class="card-body text-center">
                                    <div class="assessment-thumbnail mb-3 position-relative">
                                        <img src="{{ asset('student/assets/images/exam_img.png') }}"
                                            class="img-fluid video-thumbnail blur-image" alt="Final Exam Image">

                                        <div class="card-overlay d-flex justify-content-center align-items-center">
                                            <img src="{{ asset('student/assets/images/lock_img.png') }}" alt="Locked" style="width: 50px; height: 50px;">
                                        </div>
                                    </div>
                                    <div class="text-start">
                                        <h5 class="mb-1 text-black">Final Exam</h5>
                                        <h6 class="mb-1 text-black">
                                            {{ $subject->final_exam->duration }} minutes | {{ $subject->final_exam->total_sessions }} sessions
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('student.exam.show', $subject->final_exam->id) }}" class="text-decoration-none">
                                <div class="card h-100 text-center bg-light position-relative">
                                    <div class="card-body text-center">
                                        <div class="assessment-thumbnail mb-3 position-relative">
                                            <img src="{{ asset('student/assets/images/exam_img.png') }}"
                                                class="img-fluid video-thumbnail" alt="Final Exam Image">
                                        </div>

                                        <div class="text-start px-2">
                                            <h5 class="mb-1 text-black">Final Exam</h5>
                                            <h6 class="mb-1 text-black">
                                                {{ $subject->final_exam->duration }} minutes | {{ $subject->final_exam->total_sessions }} sessions
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>   
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var carousel = document.getElementById('carouselExampleIndicators');

        carousel.addEventListener('slid.bs.carousel', function (event) {
            let index = event.to; // Get current slide index

            // Hide all text sections
            document.querySelectorAll('.slider-text').forEach(text => text.classList.remove('active'));

            // Show the corresponding text
            if (index === 0) {
                document.getElementById('first-slider-text').classList.add('active');
            } else if (index === 1) {
                document.getElementById('second-slider-text').classList.add('active');
            } else if (index === 2) {
                document.getElementById('third-slider-text').classList.add('active');
            }
        });
    });

    // subject button script
    document.addEventListener("DOMContentLoaded", function () {
        const buttons = document.querySelectorAll(".subject-btn");
        const contents = document.querySelectorAll(".electronics-classes");
        const images = document.querySelectorAll(".subject-img");

        buttons.forEach(button => {
            button.addEventListener("click", function () {
                // Remove 'active' class from all buttons
                buttons.forEach(btn => btn.classList.remove("active"));
                this.classList.add("active");

                const targetId = this.getAttribute("data-target");

                // Hide all content sections and images
                contents.forEach(content => content.classList.remove("active-subject"));
                images.forEach(img => img.classList.remove("active-subject"));

                // Show the selected content and subject image
                document.getElementById(targetId).classList.add("active-subject");
                document.querySelector(`.subject-img[data-target="${targetId}"]`).classList.add("active-subject");
            });
        });

        const firstButton = document.querySelector('.subject-btn');
            if (firstButton) {
                const firstTarget = firstButton.getAttribute('data-target');
                document.getElementById(firstTarget)?.classList.add("active-subject");
                document.querySelector(`.subject-img[data-target="${firstTarget}"]`)?.classList.add("active-subject");
            }
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const buttons = document.querySelectorAll(".subject-btn");
        const classesSections = document.querySelectorAll(".electronics-classes");
        const imageSections = document.querySelectorAll(".subject-img");

        buttons.forEach(button => {
            button.addEventListener("click", function () {
                const target = this.getAttribute("data-target");

                // Toggle active button
                buttons.forEach(btn => btn.classList.remove("active"));
                this.classList.add("active");

                // Toggle subject image
                imageSections.forEach(img => {
                    img.classList.toggle("d-none", img.getAttribute("data-target") !== target);
                });

                // Toggle videos
                classesSections.forEach(section => {
                    section.classList.toggle("d-none", section.getAttribute("id") !== target);
                });
            });
        });
    });
</script>
<script>
    document.querySelectorAll('.modal').forEach(function(modal) {
        modal.addEventListener('hidden.bs.modal', function () {
            const video = modal.querySelector('video');
            if (video) {
                video.pause();
                video.currentTime = 0; // Optional: reset to start
            }
        });
    });
</script>

<script>
    document.querySelectorAll('video').forEach(video => {
        video.addEventListener('pause', function() {
            sendVideoStatus(video, 'paused');
        });

        video.addEventListener('play', function() {
            sendVideoStatus(video, 'in_progress');
        });

        video.addEventListener('ended', function() {
            sendVideoStatus(video, 'completed');
        });
    });

    function sendVideoStatus(video, status) {
        const videoId = video.getAttribute('data-video-id'); // You'll need to set this attribute on the video tag
        const seekPosition = Math.floor(video.currentTime);

        fetch("{{ route('video.status.update') }}", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                video_id: videoId,
                video_status: status,
                seek_position: seekPosition
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log(data.message);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const videoPlayers = document.querySelectorAll('.video-player');
        let progressUpdateIntervals = {}; // Use an object to store intervals for each video

        const sendProgress = (videoElement, eventType) => {
            const data = {
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                video_id: videoElement.dataset.videoId,
                subject_id: videoElement.dataset.subjectId,
                subject_session_id: videoElement.dataset.sessionId,
                currentTime: videoElement.currentTime,
                event: eventType, // Corrected to match backend
            };

            fetch("{{ route('video.log.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': data._token
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error('Failed to log video progress:', data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        };

        videoPlayers.forEach(video => {
            const videoId = video.dataset.videoId;

            // Event: When the video starts playing
            video.addEventListener('play', function () {
                // Clear any existing interval for this video
                if (progressUpdateIntervals[videoId]) {
                    clearInterval(progressUpdateIntervals[videoId]);
                }

                // Send a 'start' event if it's the first time playing, then an 'update'
                sendProgress(this, 'start');
                
                // Set an interval to update progress every 15 seconds
                progressUpdateIntervals[videoId] = setInterval(() => {
                    sendProgress(this, 'update');
                }, 15000); // 15 seconds
            });

            video.addEventListener('pause', function () {
                // Check if the video has NOT ended before sending a 'left' event
                if (!this.ended) {
                    // Clear the interval and send a final 'left' update
                    clearInterval(progressUpdateIntervals[videoId]);
                    sendProgress(this, 'left');
                }
            });
            // Event: When the video finishes
           video.addEventListener('ended', function() {
                clearInterval(progressUpdateIntervals[videoId]);
                sendProgress(this, 'completed');
                location.reload(); 
            });
        });

        // Handle modal close event to mark video as 'left'
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.addEventListener('hidden.bs.modal', function () {
                const video = modal.querySelector('.video-player');
                if (video && !video.paused) {
                    // This will trigger the 'pause' event listener, which sends the 'left' status
                    video.pause(); 
                }
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.video-wrapper').forEach(function (wrapper) {
            wrapper.addEventListener('click', function () {
                const video = wrapper.querySelector('video');
                const overlay = wrapper.querySelector('.play-overlay');
                if (video && overlay) {
                    video.play();
                    overlay.style.display = 'none'; // Hide play icon after click
                    video.controls = true; // Show controls on play
                }
            });
        });
    });
</script>
@endpush

@endsection