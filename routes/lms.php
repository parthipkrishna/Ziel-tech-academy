<?php


use App\Http\Controllers\lms\AssessmentController;
use App\Http\Controllers\lms\AuthController;
use App\Http\Controllers\lms\ExamReportController;
use App\Http\Controllers\lms\LiveClassReportController;
use App\Http\Controllers\lms\PaymentConfigController;
use App\Http\Controllers\lms\RecordVideoController;
use App\Http\Controllers\lms\RoleController;
use App\Http\Controllers\lms\SubjectSessionController;
use App\Http\Controllers\lms\ToolKitController;
use App\Http\Controllers\lms\ToolKitEnquiryController;
use App\Http\Controllers\lms\VideoReportController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\lms\ExcelController;
use App\Http\Controllers\lms\DashboardController;
use App\Http\Controllers\lms\UserController;
use App\Http\Controllers\lms\StudentController;
use App\Http\Controllers\lms\StudentEnrollController;
use App\Http\Controllers\lms\CourseController;
use App\Http\Controllers\lms\CourseSectionController;
use App\Http\Controllers\lms\SubjectController;
use App\Http\Controllers\lms\BannerController;
use App\Http\Controllers\lms\BatchController;
use App\Http\Controllers\lms\ExamController;
use App\Http\Controllers\lms\NotificationController;
use App\Http\Controllers\lms\FaqController;
use App\Http\Controllers\lms\InfluencerController;
use App\Http\Controllers\lms\FeedbackController;
use App\Http\Controllers\lms\FeedbackSessionController;
use App\Http\Controllers\lms\FeedbackHistoryController;
use App\Http\Controllers\lms\TopAchieverController;
use App\Http\Controllers\lms\ImportantLinkController;
use App\Http\Controllers\lms\ReferralHistoryController;
use App\Http\Controllers\lms\ReferralPaymentController;
use App\Http\Controllers\lms\LiveClassController;
use App\Http\Controllers\lms\PaymentController;
use App\Http\Controllers\lms\SubscriptionController;
use App\Http\Controllers\lms\VideoController;
use App\Http\Middleware\PreventBackHistory;

Route::controller(AuthController::class)->group(function () {
    Route::get('login','loginPage')->name('lms.login');
    Route::post('login', 'login');
    Route::post('/logout', 'logout')->name('lms.logout');
});
Route::middleware(['auth:lms', PreventBackHistory::class])->group(function () {
    // Authentication Routes
    Route::get('/', [DashboardController::class,'dashboardHome'])->name('lms.dashboard.home');
    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('lms.mobile.dashboard');

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/','dashboardHome')->name('lms.dashboard.home');
        Route::get('analytics','analytics')->name('lms.analytics');
    });
    
    
    Route::controller(AuthController::class)->group(function () {
        Route::get('dashboard','dashboard')->name('lms.dashboard');
    });
    
    //User Routes
    Route::resource('users', UserController::class)->names([
        'index' => 'lms.users',
        'create' => 'lms.add.user',
        'store' => 'lms.store.user',
        'show' => 'lms.show.user',
        'edit' => 'lms.edit.user',
        'update' => 'lms.update.user',
        'destroy' => 'lms.delete.user',   
    ]);
    Route::get('enable-user/{id}',[UserController::class,'enable'])->name('lms.enable.user');
    Route::post('/lms/user/status/{id}', [UserController::class, 'updateStatus'])->name('lms.update.user.status');
    Route::get('/users/list/ajax', [UserController::class, 'ajaxUserList'])->name('users.list.ajax');


    //Role Routes
    Route::resource('roles', RoleController::class)->names([
        'index' => 'lms.roles',
        'create' => 'lms.add.role',
        'store' => 'lms.store.role',
        'show' => 'lms.show.role',
        'edit' => 'lms.edit.role',
        'update' => 'lms.update.role',
    ]);
    Route::get('lms-roles-edit/{id}',[RoleController::class,'edit'])->name('lms.roles.edit');
    Route::post('lms/mobile/roles/{id}', [RoleController::class, 'update'])->name('lms.roles.update');
    Route::post('/roles/{id}', [RoleController::class, 'destroy'])->name('lms.roles.delete');
    Route::get('/roles/list/ajax', [RoleController::class, 'ajaxList'])->name('lms.roles.ajaxList');


    //student management
    //student records
    Route::prefix('students')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('lms.students');
        Route::get('/add', [StudentController::class, 'create'])->name('lms.add.student');
        Route::post('/store', [StudentController::class, 'store'])->name('lms.store.student');
        Route::post('/update/{id}', [StudentController::class, 'update'])->name('lms.update.student');
        Route::post('/delete/{id}', [StudentController::class, 'destroy'])->name('lms.delete.student');
        Route::get('/students/list/ajax', [StudentController::class, 'ajaxStudentList'])->name('students.list.ajax');
        Route::post('/lms/student/status/{id}', [StudentController::class, 'updateStatus'])->name('lms.update.student.status');
  
    });

    //enrollments
    Route::prefix('enrollments')->group(function () {
        Route::get('/', [StudentEnrollController::class, 'index'])->name('lms.students.enroll');
        Route::get('/add', [StudentEnrollController::class, 'create'])->name('lms.add.student.enroll');
        Route::post('/store', [StudentEnrollController::class, 'store'])->name('lms.store.student.enroll');
        Route::post('/update/{id}', [StudentEnrollController::class, 'update'])->name('lms.update.student.enroll');
        Route::post('/student-enroll/delete/{id}', [StudentEnrollController::class, 'destroy'])->name('lms.delete.student.enroll');
        Route::get('/filter', [StudentEnrollController::class, 'filter'])->name('lms.filter.studet.enroll');
       Route::get('/enrollments/list/ajax', [StudentEnrollController::class, 'ajaxEnrollmentList'])->name('enrollments.list.ajax');
      Route::get('/students/search', [StudentEnrollController::class, 'studentSearch'])->name('lms.enrollment.student.search');
    });

    //batches
    Route::prefix('batches')->controller(BatchController::class)->group(function () {
        Route::get('/','index')->name('lms.batches');
        Route::get('/add','create')->name('lms.add.batch');
        Route::post('/store','store')->name('lms.store.batch');
        Route::get('/edit/{id}','edit')->name('lms.edit.batch');
        Route::post('/update/{id}','update')->name('lms.update.batch');
        Route::post('/delete/{id}','destroy')->name('lms.delete.batch');    
        Route::post('/batch-channel/{id}','deleteBatchChannel')->name('lms.delete.batch.channel');
        Route::post('/lms/batch/status/{id}', 'updateStatus')->name('lms.update.batch.status');
        Route::get('/batches/list/ajax', 'ajaxList')->name('batches.list.ajax');
    }); 

    //feedback
    Route::prefix('feedback')->controller(FeedbackController::class)->group(function () {
        Route::get('/','index')->name('lms.feedback');
        Route::get('/edit/{id}','edit')->name('lms.edit.feedback');
        Route::get('/feedbacks/list/ajax', 'ajaxList')->name('feedbacks.list.ajax');
   
    }); 
    
    //session
     Route::prefix('feedback-session')->controller(FeedbackSessionController::class)->group(function () {
        Route::post('/store','store')->name('lms.store.feedback.session'); 
        Route::post('/update/{id}','update')->name('lms.update.feedback.session');

    }); 

    //history
    Route::prefix('feedback-history')->controller(FeedbackHistoryController::class)->group(function () {
        Route::post('/store','store')->name('lms.store.feedback.history'); 
        Route::get('/edit/{id}','edit')->name('lms.edit.feedback.history'); 
        Route::post('/update/{id}','update')->name('lms.update.feedback.history');
    }); 

    //course & learning management
    //courses
    Route::prefix('courses')->group(function () {
        Route::get('/', [CourseController::class, 'index'])->name('lms.courses');
        Route::get('/add', [CourseController::class, 'create'])->name('lms.add.course');
        Route::post('/store', [CourseController::class, 'store'])->name('lms.store.course');
        Route::post('/update/{id}', [CourseController::class, 'update'])->name('lms.update.course');
        Route::post('/delete/{id}', [CourseController::class, 'destroy'])->name('lms.delete.course');
        Route::post('/lms/course/status/{id}',[CourseController::class, 'updateStatus'])->name('lms.update.course.status');
        Route::get('/courses/list/ajax', [CourseController::class, 'ajaxCourseList'])->name('courses.list.ajax');
   
    }); 

    //course sections
    Route::prefix('course-sections')->controller(CourseSectionController::class)->group(function () {
        Route::get('/', 'index')->name('lms.course.section'); 
        Route::post('/store', 'store')->name('lms.store.course.section'); 
        Route::post('/update/{id}', 'update')->name('lms.update.course.section');
        Route::post('/delete/{id}', 'destroy')->name('lms.delete.course.section');
        Route::get('/section/ajax', 'ajaxList')->name('course.sections.ajax.list');
        Route::post('/lms/section/status/{id}','updateStatus')->name('lms.update.section.status');
    });

    //subjects
    Route::prefix('subjects')->controller(SubjectController::class)->group(function () {
        Route::get('/', 'index')->name('lms.subjects'); 
        Route::get('/add','create')->name('lms.add.subject');
        Route::post('/store', 'store')->name('lms.store.subject'); 
        Route::post('/update/{id}', 'update')->name('lms.update.subject');
        Route::post('/delete/{id}', 'destroy')->name('lms.delete.subject');
        Route::post('/lms/course/status/{id}','updateStatus')->name('lms.update.subject.status');
        Route::get('/subjects/list/ajax', 'ajaxSubjectList')->name('subjects.list.ajax');
    });

    //Subject Management
    //live-class
    Route::prefix('live-classes')->controller(LiveClassController::class)->group(function () {
        Route::get('/', 'index')->name('lms.live.classes');
        Route::get('/data', 'getLiveClasses')->name('lms.live.classes.data');
        Route::get('/add', 'create')->name('lms.add.live.class');
        Route::post('/store', 'store')->name('lms.store.live.class');
        Route::post('/update/{id}', 'update')->name('lms.update.live.class');
        Route::post('/delete/{id}', 'destroy')->name('lms.delete.live.class');
        Route::get('/get-sessions/{subject}', 'getSessions')->name('get-sessions');
    });

    //marketing management
    //notifications
    Route::prefix('notifications')->controller(NotificationController::class)->group(function () {
        Route::get('/', 'index')->name('lms.notifications');
        Route::get('/data', 'getNotifications')->name('lms.notifications.data');
        Route::get('/add', 'create')->name('lms.add.notification');
        Route::post('/store', 'store')->name('lms.store.notification');   
    }); 

    //faq
    Route::prefix('faqs')->controller(FaqController::class)->group(function () {
        Route::get('/', 'index')->name('lms.faqs');
        Route::get('/add', 'create')->name('lms.add.faq');
        Route::post('/store', 'store')->name('lms.store.faq');
        Route::post('/update/{id}', 'update')->name('lms.update.faq');
        Route::post('/delete/{id}', 'destroy')->name('lms.delete.faq'); 
        Route::get('/faqs/ajax-list', [FaqController::class, 'ajaxList'])->name('faqs.ajaxList');
 
    }); 

    //Mobile & App Content Management
    //banner
    Route::prefix('banners')->controller(BannerController::class)->group(function () {
        Route::get('/', 'index')->name('lms.banners'); 
        Route::get('/add','create')->name('lms.add.banner');
        Route::post('/store', 'store')->name('lms.store.banner'); 
        Route::post('/update/{id}', 'update')->name('lms.update.banner');
        Route::post('/delete/{id}', 'destroy')->name('lms.delete.banner');
        Route::get('/banners/ajax-list', 'ajaxList')->name('banners.ajaxList');
        Route::post('/lms/banner/status/{id}','updateStatus')->name('lms.update.banner.status');
    });

    //top-achiever
    Route::prefix('top-achievers')->controller(TopAchieverController::class)->group(function () {
        Route::get('/', 'index')->name('lms.top.achievers'); 
        Route::get('/add','create')->name('lms.add.top.achiever');
        Route::post('/store', 'store')->name('lms.store.top.achiever'); 
        Route::post('/update/{id}', 'update')->name('lms.update.top.achiever');
        Route::post('/delete/{id}', 'destroy')->name('lms.delete.top.achiever');
        Route::get('/students/search','studentSearch')->name('students.search');
        Route::post('/lms/achiever/status/{id}','updateStatus')->name('lms.update.achiever.status');
        Route::get('/top-achievers/ajax', 'ajaxList')->name('top-achievers.ajax');
    });

    //important-links
    Route::prefix('important-links')->controller(ImportantLinkController::class)->group(function () {
        Route::get('/', 'index')->name('lms.important.links'); 
        Route::get('/add','create')->name('lms.add.important.link');
        Route::post('/store', 'store')->name('lms.store.important.link'); 
        Route::post('/update/{id}', 'update')->name('lms.update.important.link');
        Route::post('/delete/{id}', 'destroy')->name('lms.delete.important.link');
        Route::post('/lms/link/status/{id}','updateStatus')->name('lms.update.link.status');
        Route::get('/important-links/ajax', 'ajaxList')->name('important-links.ajax');
    });

    // Referral & Affiliate System
    //influencers 
    Route::prefix('influencers')->controller(InfluencerController::class)->group(function () {
        Route::get('/', 'index')->name('lms.influencers');
        Route::get('/add', 'create')->name('lms.add.influencer');
        Route::post('/store', 'store')->name('lms.store.influencer');
        Route::post('/update/{id}', 'update')->name('lms.update.influencer');
        Route::post('/delete/{id}', 'destroy')->name('lms.delete.influencer'); 
        Route::get('/influencers/ajax', 'ajaxList')->name('influencers.ajax.list');
    }); 

    //history 
    Route::prefix('referral-history')->controller(ReferralHistoryController::class)->group(function () {
        Route::get('/', 'index')->name('lms.referral.history');
        Route::get('/referral/history/ajax',  'ajaxList')->name('referral.history.ajax');
    });

    //payment 
    Route::prefix('referral-payment')->controller(ReferralPaymentController::class)->group(function () {
        Route::get('/', 'index')->name('lms.referral.payment'); 
        Route::get('/add', 'create')->name('lms.add.payment');
        Route::post('/store', 'store')->name('lms.store.payment');  
        Route::post('/update/{id}', 'update')->name('lms.update.payment');
        Route::get('/influencer-total/{id}','getInfluencerTotal')->name('lms.total.payment');
        Route::get('/selected-influencer/{id}','getInfluencer')->name('lms.selected.influencer');
        Route::get('/payments/ajax-list',  'ajaxList')->name('payments.ajaxList');
    });

    //Export/Import
    Route::controller(ExcelController::class)->group(function () {
        //user
        Route::get('export-users', 'export')->name('lms.export.users');
        Route::post('import-users', 'import')->name('lms.import.users');
        //student
        Route::get('export-student', 'studentExport')->name('lms.export.student');
        Route::post('import-student', 'studentImport')->name('lms.import.student');
    });

    //Payment Records
    Route::prefix('student-payment')->controller(PaymentController::class)->group(function () {
        Route::get('/student-payments',  'index')->name('lms.payments.index');
        Route::get('/payments/ajax-list', 'ajaxList')->name('payments.record.ajaxList');
    });

    //Subscription Management
    Route::prefix('subscriptions')->controller(SubscriptionController::class)->group(function () {
        Route::get('/', 'index')->name('lms.subscriptions');
        Route::get('/subscriptions/ajax-list', 'ajaxList')->name('lms.subscriptions.ajaxList');

    });

    //Subject Sessions
    Route::prefix('subject-sessions')->controller(SubjectSessionController::class)->group(function () {
        Route::get('/subject-sessions',  'index')->name('subject-sessions.index');
        Route::get('/subject-sessions/create',  'create')->name('subject-sessions.create');
        Route::post('/subject-sessions',  'store')->name('subject-sessions.store');
        Route::get('/subject-sessions/list',  'ajaxList')->name('subject-sessions.ajaxList');
        Route::post('/subject-sessions/update/{id}',  'update')->name('subject-sessions.update');
        Route::post('/subject-sessions/delete/{id}',  'destroy')->name('subject-sessions.delete');
    });

    //Videos
    Route::prefix('videos')->controller(VideoController::class)->group(function () {
    Route::get('/', 'index')->name('lms.videos.index');
    Route::get('/ajax-list', 'ajaxList')->name('lms.videos.ajaxList');
    Route::post('/delete/{id}', 'destroy')->name('lms.videos.delete');
    Route::get('/create', 'create')->name('lms.videos.create');
    Route::post('/store', 'store')->name('lms.videos.store');
    Route::post('/update/{id}', 'update')->name('lms.videos.update');
    Route::get('/edit/{id}', 'edit')->name('lms.videos.edit');
     Route::post('/lms/video/status/{id}', 'updateStatus')->name('lms.update.video.status');
    Route::post('/videos/upload-chunk', [VideoController::class, 'uploadChunk'])->name('lms.videos.chunk.upload');

    });

    //Recorded Videos
    Route::prefix('recorded-videos')->controller(RecordVideoController::class)->group(function () {
        Route::get('/', 'index')->name('lms.recorded.videos.index');
        Route::get('/ajax-list', 'ajaxList')->name('lms.recorded.videos.ajaxList');
        Route::post('/lms/recorded-videos/status/{id}','updateStatus')->name('lms.update.recorded.video.status');
        Route::post('/delete/{id}', 'destroy')->name('lms.recorded.videos.delete');
        Route::get('/create', 'create')->name('lms.recorded.videos.create');
        Route::post('/store', 'store')->name('lms.recorded.videos.store');
        Route::post('/update/{id}', 'update')->name('lms.recorded.videos.update');
        Route::get('/edit/{id}', 'edit')->name('lms.recorded.videos.edit');
    });

    //Exam Management
    Route::prefix('exams')->controller(ExamController::class)->group(function () {
        Route::get('/', 'index')->name('lms.exams.index');
        Route::get('/create', 'create')->name('lms.exams.create');
        Route::post('/store', 'store')->name('lms.exams.store');
        Route::get('/edit/{id}', 'edit')->name('lms.exams.edit');
        Route::post('/update/{id}', 'update')->name('lms.exams.update');
        Route::post('/delete/{id}', 'destroy')->name('lms.exams.delete');
        Route::get('/ajax-list', 'ajaxList')->name('lms.exams.ajaxList');
        Route::get('/exams/{exam}/questions', 'showQuestionsPage')->name('lms.exams.questions');
        Route::post('/exams/{exam}/questions', 'storeQuestion')->name('lms.exams.storeQuestion');
        Route::post('/exam/question/{id}', 'updateQuestion')->name('lms.exam.updateQuestion');
        Route::post('/exam/question/delete/{id}', 'deleteQuestion')->name('lms.exam.deleteQuestion');
    });

    //Assessment Management
    Route::prefix('assessment')->controller(AssessmentController::class)->group(function () {
        Route::get('/', 'index')->name('lms.assessment.index');
        Route::get('/create', 'create')->name('lms.assessment.create');
        Route::post('/store', 'store')->name('lms.assessment.store');
        Route::get('/edit/{id}', 'edit')->name('lms.assessment.edit');
        Route::post('/update/{id}', 'update')->name('lms.assessment.update');
        Route::post('/delete/{id}', 'destroy')->name('lms.assessment.delete');
        Route::get('/ajax-list', 'ajaxList')->name('lms.assessment.ajaxList');
        Route::get('/exams/{exam}/questions', 'showQuestionsPage')->name('lms.assessment.questions');
        Route::post('/exams/{exam}/questions', 'storeQuestion')->name('lms.assessment.storeQuestion');
        Route::post('/assessment/question/{id}', 'updateQuestion')->name('lms.assessment.updateQuestion');
        Route::post('/assessment/question/delete/{id}', 'deleteQuestion')->name('lms.assessment.deleteQuestion');


    });

    //payment-config
    Route::prefix('payment-config')->controller(PaymentConfigController::class)->group(function () {
        Route::get('/', 'index')->name('lms.payment-config.index');
        Route::get('/payment-configs/ajax-list', 'ajaxList')->name('payment_configs.ajaxList');
        Route::post('payment-configs/{id}/toggle-status', 'toggleStatus')->name('payment-configs.toggleStatus');
        Route::get('/create', 'create')->name('lms.payment-config.create');
        Route::post('/store', 'store')->name('lms.payment-configs.store');
        Route::post('/payment-configs/{id}/update', 'update')->name('lms.payment-configs.update');
        Route::post('payment-configs/{id}/delete', 'destroy')->name('lms.payment-configs.delete');

    });

    //toolkits
    Route::prefix('toolkits')->controller(ToolKitController::class)->group(function () {
        Route::get('/', 'index')->name('lms.toolkits.index');
        Route::get('/create', 'create')->name('lms.toolkits.create');
        Route::get('/ajax-list', 'ajaxList')->name('lms.toolkits.ajaxList');
        Route::post('/store', 'store')->name('lms.toolkits.store');
        Route::get('/{id}', 'show')->name('lms.toolkits.show');
        Route::post('/{id}/update', 'update')->name('lms.toolkits.update');
        Route::delete('/{id}/delete', 'destroy')->name('lms.toolkits.delete');
        Route::post('/lms/toolkit/status/{id}','updateStatus')->name('lms.update.toolkit.status');
    });

    //toolkit enquiries

    Route::prefix('toolkit-enquiries')->controller(ToolKitEnquiryController::class)->group(function() {
        Route::get('/', 'index')->name('lms.toolkit.enqiry.index');
        Route::get('toolkit-enquiries/data',  'ajaxList')->name('lms.toolkit.enqiry.data');
        Route::get('/export', 'export')->name('lms.toolkit.enqiry.export');
        Route::post('/import', 'import')->name('lms.toolkit.enqiry.import.submit');
        Route::post('/status-update/{id}', 'update')->name('lms.toolkit.enquiry.updateStatus');
    });

    //exam reports
    Route::prefix('exam-reports')->controller(ExamReportController::class)->group(function() {
        Route::get('/', 'viewExamReportPage')->name('lms.exam.report.page');
        Route::get('/exam-report', 'examReportAjax')->name('lms.exam.report');
        Route::get('/exam-export', 'export')->name('lms.exam.report.export');  
    });

    //assessment reports
    Route::prefix('assessment-reports')->controller(ExamReportController::class)->group(function() {
        Route::get('/', 'viewAssessmentReportPage')->name('lms.assessment.report.page');
        Route::get('/assessment-report-ajax', 'assessmentReportAjax')->name('lms.assessment.report.ajax');
        Route::get('/assessment-report-export', 'exportAssessment')->name('lms.assessment.report.export');
    });

    //video reports
    Route::prefix('video-reports')->controller(VideoReportController::class)->group(function() {
        Route::get('/', 'index')->name('lms.video.report.page');
        Route::get('/video-report-ajax', 'videoReportAjax')->name('lms.video.report.ajax');
        Route::get('/video-report-export', 'videoReportExport')->name('lms.video.report.export');
    });

    //live class reports
    Route::prefix('live-class-reports')->controller(LiveClassReportController::class)->group(function() {
        Route::get('/', 'index')->name('lms.live.class.report.page');
        Route::get('/live-class-report-ajax', 'liveClassReportAjax')->name('lms.live.class.report.ajax');
        Route::get('/live-class-report-export', 'liveClassReportExport')->name('lms.live.class.report.export');
    });
});