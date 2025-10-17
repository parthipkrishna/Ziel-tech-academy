<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\StudentController;
use App\Http\Controllers\API\V1\CourseController;
use App\Http\Controllers\API\V1\SubscriptionController;
use App\Http\Controllers\API\V1\AnalyticsController;
use App\Http\Controllers\API\V1\PaymentController;
use App\Http\Controllers\API\V1\NotificationController;
use App\Http\Controllers\API\V1\HomeController;
use App\Http\Controllers\API\V1\CourseSectionController;
use App\Http\Controllers\API\V1\SubjectController;
use App\Http\Controllers\API\V1\FaqController;
use App\Http\Controllers\API\V1\ExamController;
use App\Http\Controllers\API\V1\QuestionController;
use App\Http\Controllers\API\V1\AnswerController;
use App\Http\Controllers\Api\V1\ResetPasswordController;
use App\Http\Controllers\API\V1\MCQController;
use App\Http\Controllers\API\V1\LiveClassController;
use App\Http\Controllers\API\V1\VideoController;
use App\Http\Controllers\API\V1\RecordedVideoController;
use App\Http\Controllers\API\V1\VideoSessionController;
use App\Http\Controllers\API\V1\BannerController;
use App\Http\Controllers\API\V1\PasswordResetController;
use App\Http\Controllers\API\V1\CertificateController;
use App\Http\Controllers\API\V1\VideoLogController;
use App\Http\Controllers\API\V1\ToolKitController;
use App\Http\Middleware\OptionalSanctumAuth;
use Illuminate\Auth\Notifications\ResetPassword;

Route::prefix('v1')->group(function () {

    /** ---------------------
     *  Public APIs (No Auth)
     * --------------------- */
    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'loginWithPassword')->middleware('throttle:10,1');
        Route::post('register', 'register')->middleware('throttle:10,1');
        Route::post('auth/social', 'loginWithSocialMedias')->middleware('throttle:10,1');
        Route::post('send-otp', 'requestOtp')->middleware('throttle:5,1');
        Route::post('refresh-token', 'refreshToken');

        Route::post('logout', 'logout')->middleware('auth:sanctum');
    });

    Route::prefix('student')->controller(StudentController::class)->group(function () {
        Route::post('login', 'loginWithPassword')->middleware('throttle:10,1');
        Route::post('register', 'registerStudent');
        Route::post('auth/social', 'loginWithSocialMedias')->middleware('throttle:10,1');
        Route::post('send-otp', 'requestOtp')->middleware('throttle:5,1');
        Route::post('/verify-email-otp', 'verifyEmailOtp');
        Route::post('/verify-sms-otp', 'verifySmsOtp');
    });

    Route::post('student/change-password', [ResetPasswordController::class, 'changePasswordAuthenticated']);

    Route::prefix('password')->group(function () {
        Route::post('/request-otp', [PasswordResetController::class, 'requestPasswordResetOtp']);
        Route::post('/verify-otp', [PasswordResetController::class, 'verifyResetOtp']);
        Route::post('/reset', [PasswordResetController::class, 'resetPassword']);
    });

    Route::get('faqs', [FaqController::class, 'index']);

    // Public route for temporary token
    Route::get('certificates/serve/{filename}', [CertificateController::class, 'serveTemporary'])->name('certificates.serve');

    Route::get('student/home', [HomeController::class, 'home'])
        ->middleware(OptionalSanctumAuth::class);

    Route::get('course-list', [CourseController::class, 'index']);
    Route::get('course/{id}', [CourseController::class, 'show']);
    Route::get('toolkits/{id}', [ToolKitController::class, 'show']);
    
    Route::prefix('auth')->group(function () {
        Route::post('/request-password-reset', [ResetPasswordController::class, 'requestPasswordResetOtp']);
        Route::post('/verify-reset-otp', [ResetPasswordController::class, 'verifyResetOtp']);
        Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword']);
    });
    
    /** -----------------------
     *  Secured APIs (Sanctum)
     * ----------------------- */
    Route::middleware('auth:sanctum')->group(function () {

        Route::post('notifications', [NotificationController::class, 'store']);
        Route::get('student/notifications', [NotificationController::class, 'index']);
      
        // 🔹 Profile routes (authenticated)
        Route::prefix('student/profile')->controller(StudentController::class)->group(function () {
            Route::get('/', 'show');                // Get profile info
            Route::post('/image', 'updateProfileImage'); // Update profile image
            Route::put('/', 'update');              // Update profile details
        });

        /** -----------------
         *  Course Routes
         * ----------------- */
        Route::prefix('course')->controller(CourseController::class)->group(function () {
            Route::post('', 'store');    // For creating a new course
            // Route::get('{id}', 'show');  // For showing a specific course by ID
            Route::post('{id}', 'update'); // For updating a specific course by ID
            Route::delete('{id}', 'destroy'); // For deleting a specific course by ID
            Route::post('enroll-course', 'enroll');
        });

        Route::post('course-enroll', [CourseController::class, 'enroll']);

        /** -----------------
         *  Course Sections Routes
         * ----------------- */
        Route::prefix('course-sections')->controller(CourseSectionController::class)->group(function () {
            Route::post('', 'store');      // For creating a new section
            Route::get('', 'index');       // For getting all sections
            Route::get('{id}', 'show');     // For showing a specific section by ID
            Route::put('{id}', 'update');  // For updating a specific section by ID (Use PUT or PATCH)
            Route::delete('{id}', 'destroy'); // For deleting a specific section
        });

        /** -----------------
         *  Course Module Routes
         * ----------------- */
        Route::prefix('subjects')->controller(SubjectController::class)->group(function () {
            Route::post('', 'store');      // For creating a new section
            Route::get('', 'index');       // For getting all sections
            Route::get('{id}', 'show');  // For showing a specific section by ID
            Route::put('{id}', 'update');  // For updating a specific section by ID (Use PUT or PATCH)
            Route::delete('{id}', 'destroy'); // For deleting a specific section
        });

        /** -----------------
         *  Subscription Routes
         * ----------------- */
        Route::prefix('subscription')->controller(SubscriptionController::class)->group(function () {
            Route::get('/', 'index'); // GET /api/subscription
        });
    
        /** -----------------
         *  Notifications
         * ----------------- */
        Route::controller(NotificationController::class)->group(function () {
            Route::post('notifications', 'store');
            Route::get('notifications', 'getNotifications');
        });

        /** -----------------
         *  Analytics Routes
         * ----------------- */
        Route::prefix('analytics')
            ->controller(AnalyticsController::class)
            ->group(function () {
                Route::get('/', 'index'); // ✅ Only Analytics API
            });
        /** -----------------
         *  Payment Routes
         * ----------------- */
        Route::prefix('payments')->controller(PaymentController::class)->group(function () {
            Route::get('history', 'paymentHistory');
        });

        /** -----------------
         *  FAQ
         * ----------------- */
        Route::prefix('faqs')->controller(FaqController::class)->group(function () {
            Route::post('', 'store');
            Route::get('{id}', 'show');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
        });

        /** -----------------
         *  Exams
         * ----------------- */
        Route::prefix('exams')->controller(ExamController::class)->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::get('{id}', 'show');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
        });

        /** -----------------
         *  Questions
         * ----------------- */
        Route::prefix('questions')->controller(QuestionController::class)->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::get('{id}', 'show');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
        });

        /** -----------------
         *  Answers
         * ----------------- */
        Route::prefix('answers')->controller(AnswerController::class)->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::get('{id}', 'show');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
        });

        /** -----------------
         *  MCQs
         * ----------------- */

        Route::prefix('exams')->controller(MCQController::class)->group(function () {
            /** Exam Participants **/
            Route::post('log', 'log');
            Route::post('scores', 'storeScore');
            Route::post('answers', 'storeAnswer');
            Route::post('history', 'history');
            Route::get('attempt/list', 'examAttempts');
        });

        /** -----------------
         *  Live Class
         * ----------------- */
        Route::prefix('live-classes')->controller(LiveClassController::class)->group(function () {
            Route::post('{id}/join', 'join');       // Join a live class
            Route::post('{id}/leave', 'leave');     // Leave a live class
        });

        /** -----------------
         *  Recorder videos section
         * ----------------- */

        Route::prefix('sessions')->controller(VideoSessionController::class)->group(function () {
            Route::get('', 'index');          // List all sessions
            Route::post('', 'store');         // Create a new session
            Route::get('{id}', 'show');       // Show a specific session
            Route::put('{id}', 'update');     // Update a specific session
            Route::delete('{id}', 'destroy'); // Delete a session
        });

        Route::prefix('videos')->controller(VideoController::class)->group(function () {
            Route::get('', 'index');
            Route::post('', 'store');
            Route::get('{id}', 'show');
            Route::put('{id}', 'update');
            Route::delete('{id}', 'destroy');
        });

        Route::prefix('recorded-videos')->controller(RecordedVideoController::class)->group(function () {
            Route::get('', 'index');          // List all recorded videos
            Route::post('', 'store');         // Create a new recorded video
            Route::get('{id}', 'show');       // Show a specific recorded video
            Route::put('{id}', 'update');     // Update a specific recorded video
            Route::delete('{id}', 'destroy'); // Delete a recorded video
        });

        Route::prefix('banners')->controller(BannerController::class)->group(function () {
            Route::get('', 'index');          // List all banners
            Route::post('', 'store');         // Create a new banner
            Route::get('{id}', 'show');       // Show a specific banner
            Route::post('{id}', 'update');     // Update a specific banner
            Route::delete('{id}', 'destroy'); // Delete a banner
        });

        Route::controller(HomeController::class)->group(function () {
            Route::delete('student/delete-account', 'deleteUserAccount');
        });

        Route::prefix('payments')->group(function () {
            Route::post('/checkout', [PaymentController::class, 'checkout']);
            Route::post('/confirm-free', [PaymentController::class, 'confirmFree']);
            Route::post('/confirm-paid', [PaymentController::class, 'confirmPaid']);
            Route::post('/cancel', [PaymentController::class, 'cancel']);
        });

        Route::prefix('certificates')->group(function () {
            Route::get('list/{studentId}', [CertificateController::class, 'list']);
            Route::get('{studentId}/{courseId}', [CertificateController::class, 'generate']);
        });

        Route::post('video-logs', [VideoLogController::class, 'store']);

        Route::prefix('toolkits')->group(function () {
            Route::post('/', [ToolKitController::class, 'store']);

            Route::post('/{toolKitId}/enquiry', [ToolKitController::class, 'submitEnquiry']);
            // Cancel a pending toolkit enquiry
            Route::post('/{enquiryId}/enquiry/cancel', [ToolKitController::class, 'cancelEnquiry']);
        });
    });
});
