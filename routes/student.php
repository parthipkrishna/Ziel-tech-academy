<?php

use App\Http\Controllers\student\AnalyticsController;
use App\Http\Controllers\student\AuthController;
use App\Http\Controllers\student\CourseController;
use App\Http\Controllers\student\ExamController;
use App\Http\Controllers\student\ExamHistoryController;
use App\Http\Controllers\student\LiveclassController;
use App\Http\Controllers\student\SubjectController;
use App\Http\Controllers\student\SubscriptionController;
use App\Http\Controllers\student\ReferalController;
use App\Http\Controllers\student\DashboardController;
use App\Http\Controllers\student\AssessmentController;
use App\Http\Controllers\student\MCQController;
use App\Http\Controllers\student\PaymentController;
use App\Http\Controllers\student\VideoController;
use App\Http\Middleware\PreventBackHistory;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {

    Route::get('login', 'loginPage')->name('student.login.page');
    Route::post('login', 'login')->name('student.login');
    Route::post('logout', 'logout')->name('student.logout');
    Route::get('/reset-password', 'resetpassword')->name('student.reset.password');

});

Route::middleware(['auth:student', PreventBackHistory::class])->group(function () {

    Route::prefix('student-home')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('student.dashboard.home');
        Route::get('/student/toolkit/details/{id}', [DashboardController::class, 'ToolkitDetails'])->name('student.toolkit.details');
        Route::get('/certificates', [DashboardController::class, 'showCertificates'])->name('student.certificates');
    });

    Route::prefix('analytics')->group(function () {
        Route::get('/', [AnalyticsController::class, 'index'])->name('student.portal.analytics');
    });

    Route::controller(AuthController::class)->group(function () {
        Route::get('dashboard','dashboard')->name('student.dashboard');
    });

    Route::prefix('courses')->group(function () { 
        Route::get('/', [CourseController::class, 'index'])->name('student.portal.courses');
        Route::post('/student/toolkit-enquiry', [CourseController::class, 'storeEnquiry'])->name('student.toolkit.enquiry');
        Route::get('/student/courses/{course}', [CourseController::class, 'show'])->name('student.portal.courses.show');
    });

    Route::prefix('subjects')->group(function () {
        Route::get('/', [SubjectController::class, 'index'])->name('student.portal.subjects');
        Route::get('/get-videos/{subject}', [SubjectController::class, 'getVideos'])->name('student.subjects.videos');
        Route::get('/subjects/{course}', [SubjectController::class, 'index'])->name('student.subjects.index');
    });

     Route::prefix('subscriptions')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('student.portal.subscription');
    });

     Route::prefix('referals')->group(function () {
        Route::get('/', [ReferalController::class, 'index'])->name('student.portal.referals');
    });
    
    Route::prefix('exam-mcq')->group(function () {
        Route::get('/', [ExamController::class, 'index'])->name('student.portal.exam-mcq');
        Route::post('/student/exam-submit', [ExamController::class, 'submitScore'])->name('student.exam.submit');
        Route::get('/exams/{examId}', [ExamController::class, 'show'])->name('student.exam.show');
    });

    Route::prefix('live-classes')->group(function () {
        Route::get('/', [LiveclassController::class, 'index'])->name('student.live.classes');
        Route::get('/live-class/join/{id}', [LiveclassController::class, 'join'])->name('live-class.join');
    });

    Route::prefix('exam-statuses')->group(function () {
        Route::get('/', [AssessmentController::class, 'index'])->name('student.exam.assessment');
        Route::get('/assessments/{examId}', [AssessmentController::class, 'show'])->name('student.assessment.show');
    });

    Route::prefix('videos')->group(function () {
        Route::get('/', [VideoController::class, 'index'])->name('student.portal.videos');
    });

    Route::prefix('exam-history')->group(function () {
        Route::get('/history', [ExamHistoryController::class, 'historyIndex'])->name('student.portal.history.index');
        Route::get('/history/{exam_attempt_id}', [ExamHistoryController::class, 'historyView'])->name('student.portal.history.view');
    });

  Route::prefix('payments-student')->group(function () {
        Route::post('/checkout', [PaymentController::class, 'checkout'])->name('payments-student.checkout');
        Route::post('/confirm-free', [PaymentController::class, 'confirmFree'])->name('payments-student.confirm-free');
        Route::post('/confirm-paid', [PaymentController::class, 'confirmPaid'])->name('payments-student.confirm-paid');
        Route::post('/cancel', [PaymentController::class, 'cancel'])->name('payments-student.cancel');
    });
    
    Route::post('/exams/log/store', [MCQController::class, 'log'])->name('student.exam.log');
    Route::post('/student/assessment-submit', [MCQController::class, 'submitScore'])->name('student.exam.assessment.submit');
    Route::post('/student/store-answer', [MCQController::class, 'storeAnswer'])->name('student.exam.answer.store');
    Route::post('/video-status', [VideoController::class, 'update'])->name('video.status.update');
    Route::post('/video/log', [VideoController::class, 'storeOrUpdate'])->name('video.log.store');
});
