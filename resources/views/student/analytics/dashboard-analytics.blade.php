@extends('student.layouts.layout')
@section('student-analytics')

<div class="content">
    <div class="row">
        <div class="dropdown-button mt-3">
            <div class="btn-group">
                <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> Module - 1  <span class="caret"></span> </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Dropdown link</a>
                    <a class="dropdown-item" href="#">Dropdown link</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row graph-card-section">
            <div class="col-xl-4 col-lg-4 mt-3">
                <div class="cards">
                    <h3>Quality Check <i class="ri-loader-line"></i></h3>

                    {{-- Overall Result --}}
                    @if(collect($qualityCheck)->every(fn($q) => $q === 'Pass'))
                        <button class="mt-3 btn btn-success">Pass <i class="ri-checkbox-circle-line ms-1"></i></button>
                    @else
                        <button class="mt-3 btn btn-danger">Fail <i class="ri-close-circle-line ms-1"></i></button>
                    @endif

                    {{-- Breakdown --}}
                    <div class="live-classes mt-3 d-flex flex-wrap gap-2">
                        <button class="btn {{ $qualityCheck['live_classes'] === 'Pass' ? 'btn-success' : 'btn-danger' }}">
                            Live Classes <i class="ri-check-double-line ms-1"></i>
                        </button>
                        <button class="btn {{ $qualityCheck['assessments'] === 'Pass' ? 'btn-success' : 'btn-danger' }}">
                            Assessments <i class="ri-check-double-line ms-1"></i>
                        </button>
                        <button class="btn {{ $qualityCheck['recorded_videos'] === 'Pass' ? 'btn-success' : 'btn-danger' }}">
                            Recorded Videos <i class="ri-check-double-line ms-1"></i>
                        </button>
                        <button class="btn {{ $qualityCheck['attendance'] === 'Pass' ? 'btn-success' : 'btn-danger' }}">
                            Attendance <i class="ri-check-double-line ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>
           <div class="col-xl-8 col-lg-8 mt-3 attendance-card-section">
                <div class="card">
                    <div class="attendance-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Attendance</h5>
                        <div class="attendance-header-percentage">
                            <span>{{ $attendance['percentage'] }}%</span>
                        </div>
                    </div>
                    {{-- Progress bar --}}
                   <div class="attendance-bar mt-3" style="background:#e9ecef; height:10px; border-radius:4px; overflow:hidden; display:block;">
                        <div style="height:100%; width:{{ $attendance['percentage'] }}%; background:#4CAF50; border-radius:4px 0 0 4px; transition: width .35s ease;"></div>
                   </div>
                    <p class="mt-2">Presence last {{ count($attendance['days']) }} days</p>
                    {{-- Attendance days --}}
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        @foreach($attendance['days'] as $day)
                            <div class="p-2 rounded text-center 
                                {{ $day['is_present'] ? 'bg-success text-white' : 'bg-danger text-white' }}"
                                style="min-width: 40px;"
                                title="{{ $day['date'] }}">
                                {{ $day['is_present'] ? 'P' : 'A' }}
                            </div>
                        @endforeach
                    </div>
                    {{-- Status message --}}
                    <div class="status-message mt-3">
                        {{ $attendance['status'] }}
                    </div>
                </div>
            </div>
        </div>   
        <div class="container-fluid my-3">
            <div class="row graph-section">
                <div class="col-xl-3">
                    <div class="card" style="background-color: transparent !important;box-shadow: none !important;">
                        <h4 class="header-title mb-4">Syllabus Tracker</h4>
                        <div dir="ltr">
                            <div id="update-donut" class="apex-charts"
                                data-colors="#727cf5,#6c757d,#0acf97,#fa5c7c">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="row subject-section">
                    @forelse($subjects as $subject)
                        <div class="col-lg-4 col-md-6 mt-3">
                            <a href="{{ route('student.portal.subjects') }}" style="text-decoration: none; color: inherit;">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="icon-bg">
                                            <i class="ri-book-line"></i> {{-- You can change icon here --}}
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
    </div>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Laravel passes syllabusTracker to JS
        let syllabusData = @json($syllabusTracker);

        // If multiple courses, merge totals
        let totalProgress = 0;
        let totalCourses = 0;
        syllabusData.forEach(course => {
            totalProgress += course.total_progress;
            totalCourses++;
        });
        let averageProgress = totalCourses > 0 ? (totalProgress / totalCourses) : 0;

        var options = {
            chart: { type: 'donut' },
            labels: ['Completed', 'Remaining'],
            series: [averageProgress, 100 - averageProgress],
            colors: ['#06bd89ff', '#d4405eff'],
            legend: { position: 'bottom' },
        };

        var chart = new ApexCharts(document.querySelector("#update-donut"), options);
        chart.render();
    });
</script>
@endpush

@endsection
            