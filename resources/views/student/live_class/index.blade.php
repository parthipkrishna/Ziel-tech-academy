@extends('student.layouts.layout')
@section('student-dashboard')

<div class="row mt-2">
    <div class="card card-h-100 border-none"
        style="padding: 15px 20px 20px 20px; background: var(--Congress-Blue-50, #F0F7FF); border-radius: 15px;">
        <div class="row live-section">
            <div class="col-xl-12 col-lg-12">
                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                <h4 class="mb-3">Live Classes</h4>
                <div class="live-classes-list">
                    @forelse($data['all_live_classes'] as $class)
                        <div class="live-class-item mb-2 p-3" style="background: rgba(255, 255, 255, 0.9); border-radius: 8px;">
                             <h6>
                                <span class="badge bg-danger text-white me-1">LIVE</span>
                                <i class="ri-wireless-charging-line me-1"></i> Class
                            </h6>
                            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">   
                                <img src="{{ $class->thumbnail_image ? asset('storage/' . $class->thumbnail_image) : asset('student/assets/images/small/liveClass-img.webp') }}"
                                    class="img-fluid mb-2 mb-md-0" style="width: 100%; max-width: 230px; height: 190px; border-radius: 6px; object-fit: cover;" alt="{{ $class->name ?? 'Live Class' }}">   
                                <div class="class-details ms-md-3 flex-grow-1">                                
                                    <h5 class="mt-1">{{ $class->name }}</h5>
                                    <p style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; word-break: break-word;">
                                        {{ $class->short_summary ?? 'No summary available.' }}
                                    </p>
                                    <div class="time-calendar mb-1">
                                        <span style="font-size: 14px;">
                                            <i class="ri-calendar-line me-1"></i>
                                            {{ \Carbon\Carbon::parse($class->start_time)->format('l') }}
                                            <i class="ri-forbid-fill me-1 ms-1"></i>
                                            {{ \Carbon\Carbon::parse($class->start_time)->format('h:i A') }} -
                                            {{ \Carbon\Carbon::parse($class->end_time)->format('h:i A') }}
                                        </span>
                                    </div>
                                     <a href="{{ route('live-class.join', $class->id) }}" class="btn btn-primary btn-sm mt-2">
                                        Join Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center p-3">
                            <h5 class="text-muted">No live classes found</h5>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
});
</script>


<script>
    // Initialize the carousel with swipe functionality
    document.addEventListener("DOMContentLoaded", function () {
        const carousel = document.querySelector("#liveClassCarousel");
        
        // Enable touch swiping
        let touchStartX = 0;
        let touchEndX = 0;
        
        carousel.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, false);
        
        carousel.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, false);
        
        function handleSwipe() {
            if (touchEndX < touchStartX - 50) {
                // Swipe left - next slide
                new bootstrap.Carousel(carousel).next();
            }
            if (touchEndX > touchStartX + 50) {
                // Swipe right - prev slide
                new bootstrap.Carousel(carousel).prev();
            }
        }
        
        // Optional: Auto-advance the carousel every 5 seconds
        const myCarousel = new bootstrap.Carousel(carousel, {
            interval: 5000,
            wrap: true
        });    
        
    });
</script>
<style>
.live-classes-list {
    width: 100%;
    box-sizing: border-box;
}

.live-class-item {
    transition: transform 0.2s;
    width: 100%;
    box-sizing: border-box;
}

.live-class-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
}

.class-details {
    width: 100%;
    box-sizing: border-box;
}

.badge {
    font-size: 12px;
    padding: 4px 8px;
}

.avatar-group {
    display: flex;
    align-items: center;
    gap: 5px;
}
.live-section a {
    background: var(--Congress-Blue-800, #0B4E89);
    padding: 10px 30px;
    border: 0px;
    border-radius: 30px;
    font-size: 18px;
    color: #fff;
}

@media (max-width: 767px) {
    .live-class-item {
        padding: 10px;
    }

    .class-details {
        text-align: center;
    }

    .avatar-group {
        justify-content: center;
    }

    .btn-sm {
        padding: 5px 10px;
        font-size: 14px;
    }

    img {
        max-width: 150px;
    }
}
</style>
@endsection