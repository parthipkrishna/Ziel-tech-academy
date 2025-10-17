<div class="leftside-menu">

    <!-- Brand Logo Light -->
    <a href="{{ route('lms.dashboard.home') }}" class="logo logo-light">
        <span class="logo-lg">
            <img src="{{ asset('dashboard/logo/ziel-logo-2.png') }}" alt="logo">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('dashboard/logo/ziel_sidebar.jpg') }}" alt="small logo">
        </span>
    </a>

    <!-- Brand Logo Dark -->
    <a href="{{ route('lms.dashboard.home') }}" class="logo logo-dark">
        <span class="logo-lg">
            <img src="{{ asset('dashboard/logo/ziel-logo-2.png') }}" alt="dark logo">
        </span>
        <span class="logo-sm">
            <img src="{{ asset('dashboard/logo/ziel_sidebar.jpg') }}" alt="small logo">
        </span>
    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <div class="button-sm-hover" data-bs-toggle="tooltip" data-bs-placement="right" title="Show Full Sidebar">
        <i class="ri-checkbox-blank-circle-line align-middle"></i>
    </div>

    <!-- Full Sidebar Menu Close Button -->
    <div class="button-close-fullsidebar">
        <i class="ri-close-fill align-middle"></i>
    </div>

    <!-- Sidebar -->
    <div class="h-100" id="leftside-menu-container" data-simplebar>
        <!-- Leftbar User -->
        <div class="leftbar-user">
            <a href="pages-profile.html">
                <img src="assets/images/users/avatar-1.jpg" alt="user-image" height="42" class="rounded-circle shadow-sm">
                <span class="leftbar-user-name mt-2">Dominic Keller</span>
            </a>
        </div>

        <!--- Sidemenu -->
        <ul class="side-nav">

            <li class="side-nav-title">Analytics & Insights</li>
            <li class="side-nav-item">
                <a href="{{ route('lms.dashboard') }}" class="side-nav-link">
                    <i class="uil-calender"></i>
                    <span> Dashboard </span>
                </a>
            </li>

            <!-- User Management -->
            @if(auth()->user()->hasPermission('user-access-management-view'))
            <li class="side-nav-title">User & Access Management</li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarUser" aria-expanded="false" aria-controls="sidebarUser" class="side-nav-link">
                    <i class="uil-users-alt"></i>
                    <span>Manage Users</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarUser">
                    <ul class="side-nav-second-level">
                        @if(auth()->user()->hasPermission('users', 'view'))
                        <li>
                            <a href="{{ route('lms.users') }}">Users</a>
                        </li>
                        @endif
                        @if(auth()->user()->hasPermission('roles', 'view'))
                        <li>
                            <a href="{{ route('lms.roles') }}">Roles And Permissions</a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif
            

            <!-- Student Management -->
            @if(auth()->user()->hasPermission('student-management-view'))
            <li class="side-nav-title">Student Management</li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarStudent" aria-expanded="false" aria-controls="sidebarStudent" class="side-nav-link">
                    <i class=" uil-graduation-hat"></i>
                    <span> Manage Students  </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarStudent">
                    <ul class="side-nav-second-level">
                         @if(auth()->user()->hasPermission('students', 'view'))
                        <li>
                            <a href="{{ route('lms.students') }}">Student Records </a>
                        </li>
                        @endif
                        @if(auth()->user()->hasPermission('enrollments.view'))
                        <li>
                            <a href="{{ route('lms.students.enroll') }}">Enrollments</a>
                        </li>
                        @endif

                        @if(auth()->user()->hasPermission('feedback.view'))
                        <li>
                            <a href="{{ route('lms.feedback') }}">Student Feedback</a>
                        </li>
                        @endif

                        @if(auth()->user()->hasPermission('batches.view'))
                        <li>
                            <a href="{{ route('lms.batches') }}">Student Batches</a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif

            <!-- Course Management -->
            @if(auth()->user()->hasPermission('course-learning-management-view'))
            <li class="side-nav-title">Course & Learning Management</li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarCourse" aria-expanded="false" aria-controls="sidebarCourse" class="side-nav-link">
                    <i class="uil-notes"></i>
                    <span>Manage Courses</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarCourse">
                    <ul class="side-nav-second-level">
                        @if(auth()->user()->hasPermission('courses.view'))
                        <li>
                            <a href="{{ route('lms.courses') }}">Course Records</a>
                        </li>
                        @endif

                        @if(auth()->user()->hasPermission('course-sections.view'))
                        <li>
                            <a href="{{ route('lms.course.section') }}">Sections</a>
                        </li>
                        @endif

                        @if(auth()->user()->hasPermission('subjects.view'))
                        <li>
                            <a href="{{ route('lms.subjects') }}">Subjects</a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif

            <!-- Subject Management -->
            @if(auth()->user()->hasPermission('subjects-management-view'))
            <li class="side-nav-title">Subjects Management</li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarSubjects" aria-expanded="false" aria-controls="sidebarSubjects" class="side-nav-link">
                    <i class="uil-copy-alt"></i>
                    <span> Manage Subjects  </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarSubjects">
                    <ul class="side-nav-second-level">
                        @if(auth()->user()->hasPermission('subject-sessions.view'))
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarSubjectSession" aria-expanded="false" aria-controls="sidebarRecordSession">
                                <span> Subject Session </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarSubjectSession">
                                <ul class="side-nav-third-level">
                                    <li>
                                        <a href="{{ route('subject-sessions.index') }}">Subject Sessions</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        @endif
                        @if(auth()->user()->hasPermission('recorded-session.view'))
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarRecordSession" aria-expanded="false" aria-controls="sidebarRecordSession">
                                <span> Recorded Sessions </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarRecordSession">
                                <ul class="side-nav-third-level">
                                    @if(auth()->user()->hasPermission('videos.view'))
                                    <li>
                                        <a href="{{ route('lms.videos.index') }}">Videos</a>
                                    </li>
                                    @endif
                                    @if(auth()->user()->hasPermission('recorded-videos.view'))
                                    <li>
                                        <a href="{{ route('lms.recorded.videos.index') }}">Recorded Videos</a>
                                    </li>
                                    @endif
                                    @if(auth()->user()->hasPermission('video-reports.view'))
                                    <li>
                                        <a href="{{ route('lms.video.report.page') }}">Video Reports</a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                        @endif
                         @if(auth()->user()->hasPermission('manage-live-classes.view'))
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarLive" aria-expanded="false" aria-controls="sidebarLive">
                                <span>Manage Live Classes </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarLive">
                                <ul class="side-nav-third-level">
                                    @if(auth()->user()->hasPermission('live-classes.view'))
                                    <li>
                                        <a href="{{ route('lms.live.classes') }}">Classes</a>
                                    </li>
                                    @endif
                                    @if(auth()->user()->hasPermission('live-class-reports.view'))
                                    <li>
                                        <a href="{{ route('lms.live.class.report.page') }}">Reports</a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                        @endif
                         @if(auth()->user()->hasPermission('manage-assessment.view'))
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarAssessments" aria-expanded="false" aria-controls="sidebarAssessments">
                                <span>Manage Assessments </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarAssessments">
                                <ul class="side-nav-third-level">
                                    @if(auth()->user()->hasPermission('assessments.view'))
                                    <li>
                                        <a href="{{ route('lms.assessment.index') }}">Assessments </a>
                                    </li>
                                    @endif
                                    @if(auth()->user()->hasPermission('assessment-reports.view'))
                                    <li>
                                        <a href="{{ route('lms.assessment.report.page') }}">Reports </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                        @endif
                        @if(auth()->user()->hasPermission('manage-exams.view'))
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarExams" aria-expanded="false" aria-controls="sidebarExams">
                                <span>Manage Exams </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarExams">
                                <ul class="side-nav-third-level">
                                    @if(auth()->user()->hasPermission('exams.view'))
                                    <li>
                                        <a href="{{ route('lms.exams.index') }}">Exams</a>
                                    </li>
                                    @endif
                                    @if(auth()->user()->hasPermission('exam-reports.view'))
                                    <li>
                                        <a href="{{ route('lms.exam.report.page') }}">Reports </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif
            @if(auth()->user()->hasPermission('toolkit-management-view'))
            <li class="side-nav-title">Toolkit Management</li>
                @if(auth()->user()->hasPermission('toolkits.view'))
                    <li class="side-nav-item">
                        <a href="{{ route('lms.toolkits.index') }}" class="side-nav-link">
                            <i class="uil-bell"></i>
                            <span> Toolkits </span>
                        </a>
                    </li>
                    @endif

                    @if(auth()->user()->hasPermission('toolkit-enquiries.view'))
                    <li class="side-nav-item">
                        <a href="{{ route('lms.toolkit.enqiry.index') }}" class="side-nav-link">
                            <i class="uil-comments-alt"></i>
                            <span> Toolkit Enquiries </span>
                        </a>
                    </li>
                    @endif
                @endif

            <!-- Marketing Management -->
            @if(auth()->user()->hasPermission('marketing-management-view'))
            <li class="side-nav-title">Marketing Management</li>
                @if(auth()->user()->hasPermission('notifications.view'))
                    <li class="side-nav-item">
                        <a href="{{ route('lms.notifications') }}" class="side-nav-link">
                            <i class="uil-bell"></i>
                            <span> Notifications </span>
                        </a>
                    </li>
                    @endif

                    @if(auth()->user()->hasPermission('faqs.view'))
                    <li class="side-nav-item">
                        <a href="{{ route('lms.faqs') }}" class="side-nav-link">
                            <i class="uil-comments-alt"></i>
                            <span> FAQs </span>
                        </a>
                    </li>
                    @endif
                @endif

            <!-- Payment Management -->
            @if(auth()->user()->hasPermission('payments-subscriptions-view'))
            <li class="side-nav-title"> Payments & Subscriptions </li>
            @if(auth()->user()->hasPermission('manage-payments.view'))
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarPayment" aria-expanded="false" aria-controls="sidebarPayment" class="side-nav-link">
                    <i class=" uil-usd-square"></i>
                    <span>Manage Payments</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarPayment">
                    <ul class="side-nav-second-level">
                        @if(auth()->user()->hasPermission('payments.view'))
                        <li>
                            <a href="{{ route('lms.payments.index') }}">Payments Records</a>
                        </li>
                        @endif
                        @if(auth()->user()->hasPermission('payment-config.view'))
                        <li>
                            <a href="{{ route('lms.payment-config.index') }}">Payment Config</a>
                        </li>
                        @endif
                        @if(auth()->user()->hasPermission('payment-links.view'))
                        <li>
                            <a href="apps-chat.html">Payment Links</a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif
            @if(auth()->user()->hasPermission('subscriptions.view'))
            <li class="side-nav-item">
                <a href="{{ route('lms.subscriptions') }}" class="side-nav-link">
                    <i class=" uil-presentation-check"></i>
                    <span> Subscriptions </span>
                </a>
            </li>
            @endif
            @endif

            @if(auth()->user()->hasPermission('mobile-app-content-management-view'))
                <li class="side-nav-title">Mobile & App Content Management</li>

                @if(auth()->user()->hasPermission('banners.view'))
                    <li class="side-nav-item">
                        <a href="{{ route('lms.banners') }}" class="side-nav-link">
                            <i class="uil-ticket"></i>
                            <span> Banners </span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->hasPermission('top-achievers.view'))
                    <li class="side-nav-item">
                        <a href="{{ route('lms.top.achievers') }}" class="side-nav-link">
                            <i class="uil-award"></i>
                            <span> Top Achievers </span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->hasPermission('important-links.view'))
                    <li class="side-nav-item">
                        <a href="{{ route('lms.important.links') }}" class="side-nav-link">
                            <i class="uil-link-alt"></i>
                            <span> Important Links </span>
                        </a>
                    </li>
                @endif
            @endif

            {{-- Referral & Affiliate System --}}
            @if(auth()->user()->hasPermission('referral-affiliate-system-view'))
                <li class="side-nav-title">Referral & Affiliate System</li>

                @if(auth()->user()->hasPermission('influencers.view'))
                    <li class="side-nav-item">
                        <a href="{{ route('lms.influencers') }}" class="side-nav-link">
                            <i class="uil-layer-group"></i>
                            <span> Influencers </span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->hasPermission('referral-history.view'))
                    <li class="side-nav-item">
                        <a href="{{ route('lms.referral.history') }}" class="side-nav-link">
                            <i class="uil-layer-group"></i>
                            <span> History </span>
                        </a>
                    </li>
                @endif

                @if(auth()->user()->hasPermission('referral-payment.view'))
                    <li class="side-nav-item">
                        <a href="{{ route('lms.referral.payment') }}" class="side-nav-link">
                            <i class="uil-book-reader"></i>
                            <span> Payment </span>
                        </a>
                    </li>
                @endif
            @endif
        </ul>
        <!--- End Sidemenu -->

        <div class="clearfix"></div>
    </div>
</div>