@extends('lms.layout.layout')
@section('content')
<div class="content">

    <!-- Start Content-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <form class="d-flex">
                            <a href="javascript:void(0);" class="btn btn-primary ms-2" onclick="location.reload();">
                                <i class="mdi mdi-autorenew"></i>
                            </a>
                        </form>
                    </div>
                    <h4 class="page-title">Dashboard</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-5 col-lg-6">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card widget-flat">
                            <div class="card-body">
                                <div class="float-end">
                                    <i class="mdi mdi-school widget-icon"></i>
                                </div>
                                <h5 class="text-muted fw-normal mt-0" title="Total Students">Total Students</h5>
                                <h3 class="mt-3 mb-3">{{ $students }}</h3>
                                <p class="mb-0 text-muted">
                                    <span class="me-2 {{ $studentsChange >= 0 ? 'text-success' : 'text-danger' }}">
                                        <i class="mdi {{ $studentsChange >= 0 ? 'mdi-arrow-up-bold' : 'mdi-arrow-down-bold' }}"></i>
                                        {{ abs($studentsChange) }}%
                                    </span>
                                    <span class="text-nowrap">Since last month</span>
                                </p>
                            </div> <!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->

                    <div class="col-sm-6">
                        <div class="card widget-flat">
                            <div class="card-body">
                                <div class="float-end">
                                    <i class="mdi mdi-account-check widget-icon"></i>
                                </div>
                                <h5 class="text-muted fw-normal mt-0">Active Enrollments</h5>
                                <h3 class="mt-3 mb-3">{{ $activeEnrollments }}</h3>
                                <p class="mb-0 text-muted">
                                    <span class="me-2 {{ $activeEnrollmentsChange >= 0 ? 'text-success' : 'text-danger' }}">
                                        <i class="mdi {{ $activeEnrollmentsChange >= 0 ? 'mdi-arrow-up-bold' : 'mdi-arrow-down-bold' }}"></i>
                                        {{ abs($activeEnrollmentsChange) }}%
                                    </span>
                                    <span class="text-nowrap">Since last month</span>
                                </p>
                            </div><!-- end card-body-->
                        </div> <!-- end card-->
                    </div> <!-- end col-->
                </div> <!-- end row -->
                

                <div class="row">
                    <div class="col-sm-6">
                        <div class="card widget-flat">
                            <div class="card-body">
                                <div class="float-end">
                                    <i class="mdi mdi-account-plus widget-icon"></i>
                                </div>
                                <h5 class="text-muted fw-normal mt-0">New <br>Enrollments</h5>
                                <h3 class="mt-3 mb-3">{{ $newEnrollments }}</h3>
                                <p class="mb-0 text-muted">
                                    <span class="me-2 {{ $newEnrollmentsChange >= 0 ? 'text-success' : 'text-danger' }}">
                                        <i class="mdi {{ $newEnrollmentsChange >= 0 ? 'mdi-arrow-up-bold' : 'mdi-arrow-down-bold' }}"></i>
                                        {{ abs($newEnrollmentsChange) }}%
                                    </span><br>
                                    <span class="text-nowrap">This month</span>
                                </p>
                            </div>
                        </div> <!-- end card-->
                    </div> <!-- end col-->

                    <div class="col-sm-6">
                        {{-- <div class="card widget-flat">
                            <div class="card-body">
                                <div class="float-end">
                                    <i class="mdi mdi-account-remove widget-icon"></i>
                                </div>
                                <h5 class="text-muted fw-normal mt-0">Cancelled <br>Enrollments</h5>
                                <h3 class="mt-3 mb-3">{{ $cancelledEnrollments }}</h3>
                                <p class="mb-0 text-muted">
                                    <span class="me-2 {{ $cancelledEnrollmentsChange >= 0 ? 'text-success' : 'text-danger' }}">
                                        <i class="mdi {{ $cancelledEnrollmentsChange >= 0 ? 'mdi-arrow-up-bold' : 'mdi-arrow-down-bold' }}"></i>
                                        {{ abs($cancelledEnrollmentsChange) }}%
                                    </span><br>
                                    <span class="text-nowrap">Since last month</span>
                                </p>
                            </div>
                        </div> <!-- end card--> --}}
                    </div> <!-- end col-->
                </div> <!-- end row -->
                <!-- end row -->
            </div> <!-- end col -->

            <div class="col-xl-7 col-lg-6">
                <div class="card card-h-100">
                    <div class="d-flex card-header justify-content-between align-items-center">
                        <h4 class="header-title">Monthly Enrollments</h4>
                        <div class="dropdown">
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:void(0);" class="dropdown-item">Yearly Summary</a>
                                <a href="javascript:void(0);" class="dropdown-item">Export</a>
                            </div>
                        </div>
                    </div>
                <div class="card-body pt-0">
                    <div dir="ltr">
                        <div id="monthly-enrollments-bar" class="apex-charts" data-colors="#0d6efd"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end col -->
        </div>
        <!-- end row -->
         <div class="row mt-3">
            <div class="col-sm-6 col-xl-3 mb-3">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-book-open-page-variant widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0">Total Courses</h5>
                        <h3 class="mt-3 mb-3">{{ $total_course }}</h3>
                    </div>
                </div>
            </div>

            <!-- Total Subjects -->
            <div class="col-sm-6 col-xl-3 mb-3">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-book-multiple widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0">Total Subjects</h5>
                        <h3 class="mt-3 mb-3">{{ $total_subjects }}</h3>
                    </div>
                </div>
            </div>

            <!-- Live Classes (Upcoming/Completed) -->
            <div class="col-sm-6 col-xl-3 mb-3">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-video-plus widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0">Live Classes</h5>
                        <h3 class="mt-3 mb-3">{{ $live_classes }}</h3>
                    </div>
                </div>
            </div>

            <!-- Total Exams -->
            <div class="col-sm-6 col-xl-3 mb-3">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-file-document widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0">Total Exams</h5>
                        <h3 class="mt-3 mb-3">{{ $total_exams }}</h3>
                    </div>
                </div>
            </div>

            <!-- Total Videos Uploaded -->
            <div class="col-sm-6 col-xl-3 mb-3">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-video widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0">Videos Uploaded</h5>
                        <h3 class="mt-3 mb-3">{{ $total_videos }}</h3>
                    </div>
                </div>
            </div>

            <!-- Influencer Onboarded Count -->
            <div class="col-sm-6 col-xl-3 mb-3">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-star-circle widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0">Influencers Onboarded</h5>
                        <h3 class="mt-3 mb-3">{{ $influencers }}</h3>
                    </div>
                </div>
            </div>
            <!-- Total batches -->
            <div class="col-sm-6 col-xl-3 mb-3">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-star-circle widget-icon"></i>
                        </div>
                        <h5 class="text-muted fw-normal mt-0">Total Batches</h5>
                        <h3 class="mt-3 mb-3">{{ $batches }}</h3>
                    </div>
                </div>
            </div>
        </div> 
        <div class="row">
    <!-- Line Chart - 7 columns -->
    <!-- <div class="col-xl-7 col-lg-7">
        <div class="card card-h-100">
            <div class="d-flex card-header justify-content-between align-items-center">
                <h4 class="header-title">Live Classes Attendance Trends</h4>
            </div>
            <div class="card-body pt-0">
                <div dir="ltr">
                    <div id="live-classes-attendance-chart" class="apex-charts" data-colors="#727cf5"></div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Donut Chart - 5 columns -->
    <!-- <div class="col-xl-5 col-lg-5">
        <div class="card card-h-100">
            <div class="d-flex card-header justify-content-between align-items-center">
                <h4 class="header-title">Exam Participation & Pass Rate</h4>
            </div>
            <div class="card-body">
                <div id="exam-pass-donut-chart" class="apex-charts" style="min-height: 300px;"></div>
            </div>
        </div>
    </div> -->
</div>

@php $tableHeight = '320px'; @endphp

<div class="row">
    <!-- 1. Top Performing Students -->
    <!-- <div class="col-xl-6 col-lg-12">
        <div class="card h-100">
            <div class="d-flex card-header justify-content-between align-items-center">
                <h4 class="header-title">Top Performing Students</h4>
            </div>
            <div class="card-body pt-0" style="min-height: {{ $tableHeight }};">
                <div class="table-responsive" style="max-height: {{ $tableHeight }}; overflow-y: auto;">
                    @if($topStudents->count())
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Admission No</th>
                                    <th>Exams Taken</th>
                                    <th>Avg. Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topStudents as $student)
                                    <tr>
                                        <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                        <td>{{ $student->admission_number ?? 'N/A' }}</td>
                                        <td>{{ $student->exams_taken }} <span class="text-muted font-13">Exams</span></td>
                                        <td>{{ round($student->avg_score, 2) }} <span class="text-muted font-13">Avg. Score</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="d-flex justify-content-center align-items-center" style="height: {{ $tableHeight }};">
                            <span class="text-muted">No data found.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div> -->
    <div class="col-xl-6 col-lg-12">
        <div class="card h-100">
            <div class="d-flex card-header justify-content-between align-items-center">
                <h4 class="header-title">Course-wise Performance Status</h4>
            </div>
            <div class="card-body pt-0" style="min-height: {{ $tableHeight }};">
                <div class="table-responsive" style="max-height: {{ $tableHeight }}; overflow-y: auto;">
                    @if($coursePerformance->count())
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Enrolled Students</th>
                                    <th>Completed</th>
                                    <th>Average Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($coursePerformance as $course)
                                    <tr>
                                        <td>{{ $course->course_name }}</td>
                                        <td>{{ $course->total_enrolled }}</td>
                                        <td>{{ $course->completed_count }}</td>
                                        <td>{{ $course->avg_score ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="d-flex justify-content-center align-items-center" style="height: {{ $tableHeight }};">
                            <span class="text-muted">No data found.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Recently Enrolled Students -->
    <div class="col-xl-6 col-lg-12">
        <div class="card h-100">
            <div class="d-flex card-header justify-content-between align-items-center">
                <h4 class="header-title">Recently Enrolled Students</h4>
            </div>
            <div class="card-body pt-0" style="min-height: {{ $tableHeight }};">
                <div class="table-responsive" style="max-height: {{ $tableHeight }}; overflow-y: auto;">
                    @if($recentEnrollments->count())
                        <table class="table table-centered table-nowrap table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Enrolled At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentEnrollments as $enroll)
                                    <tr>
                                        <td>{{ $enroll->first_name }} {{ $enroll->last_name }}</td>
                                        <td>{{ $enroll->course_name }}</td>
                                        <td>
                                            <span class="text-muted font-13">
                                                {{ \Carbon\Carbon::parse($enroll->created_at)->format('d M Y, h:i A') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="d-flex justify-content-center align-items-center" style="height: {{ $tableHeight }};">
                            <span class="text-muted">No data found.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

    {{-- <div class="row mt-4">
        <!-- 4. Recently Completed Exams -->
        <div class="col-xl-6 col-lg-12">
            <div class="card h-100">
                <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Recently Completed Exams</h4>
                </div>
                <div class="card-body pt-0" style="min-height: {{ $tableHeight }};">
                    <div class="table-responsive" style="max-height: {{ $tableHeight }}; overflow-y: auto;">
                        @if($recentCompletedExams->count())
                            <table class="table table-centered table-nowrap table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Exam Name</th>
                                        <th>Subject</th>
                                        <th>Completed At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentCompletedExams as $exam)
                                        <tr>
                                            <td>{{ $exam->exam_name }}</td>
                                            <td>{{ $exam->subject_name }}</td>
                                            <td>
                                                <span class="text-muted font-13">
                                                    {{ \Carbon\Carbon::parse($exam->updated_at)->format('d M Y, h:i A') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="d-flex justify-content-center align-items-center" style="height: {{ $tableHeight }};">
                                <span class="text-muted">No data found.</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Watch Time vs. Total Content -->
        <div class="col-xl-6 col-lg-12">
            <div class="card h-100">
                <div class="d-flex card-header justify-content-between align-items-center">
                    <h4 class="header-title">Video Watch Time vs. Total Content</h4>
                </div>
                <div class="card-body pt-0">
                    @if($videoStats->count())
                        <div style="max-height: 350px; overflow-y: auto;">
                            @foreach($videoStats as $video)
                                <div class="mb-3">
                                    <h5 class="font-14 fw-normal">{{ $video->title }}</h5>
                                    <div class="progress mb-1" style="height: 12px;">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ $video->completion_rate }}%;"
                                            aria-valuenow="{{ $video->completion_rate }}"
                                            aria-valuemin="0" aria-valuemax="100">
                                            {{ $video->completion_rate }}%
                                        </div>
                                    </div>
                                    <small class="text-muted">Completed: {{ $video->completed_count }} / {{ $video->total_views }}</small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No video statistics available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div> --}}
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var options = {
            chart: {
                type: 'bar',
                height: 285
            },
            series: [{
                name: 'Enrollments',
                data: @json($monthlyEnrollments)
            }],
            xaxis: {
                categories: [
                    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                ]
            },
            colors: ['#0d6efd'],
            dataLabels: {
                enabled: true
            }
        };

        var chart = new ApexCharts(document.querySelector("#monthly-enrollments-bar"), options);
        chart.render();
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var options = {
            chart: {
                height: 350,
                type: 'line',
                zoom: { enabled: false }
            },
            series: [{
                name: 'Attendees',
                data: @json($attendanceTrends)
            }],
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                             'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },
            stroke: {
                curve: 'smooth'
            },
            colors: ['#727cf5'],
            dataLabels: {
                enabled: true
            }
        };

        var chart = new ApexCharts(document.querySelector("#live-classes-attendance-chart"), options);
        chart.render();
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var options = {
            chart: {
                type: 'donut',
                height: 300
            },
            labels: ['Passed', 'Failed', 'Not Attempted'],
            series: [
                {{ $examStats['passed'] }},
                {{ $examStats['failed'] }},
                {{ $examStats['not_attempted'] }}
            ],
            colors: ['#0acf97', '#fa5c7c', '#f7b84b'],
            dataLabels: {
                enabled: true
            },
            legend: {
                position: 'bottom'
            }
        };

        var chart = new ApexCharts(document.querySelector("#exam-pass-donut-chart"), options);
        chart.render();
    });
</script>
@endsection