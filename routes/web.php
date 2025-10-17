<?php

use App\Http\Controllers\dashboard\admin\AdminCampusfacilityController;
use App\Http\Controllers\dashboard\admin\AuthController;
use App\Http\Controllers\dashboard\admin\IndexController;
use App\Http\Controllers\dashboard\admin\UserController;
use App\Http\Controllers\dashboard\admin\HomeController;
use App\Http\Controllers\dashboard\admin\AdminRoleController;
use App\Http\Controllers\dashboard\admin\AdminGalleryController;
use App\Http\Controllers\dashboard\admin\AdminCampusController;
use App\Http\Controllers\dashboard\admin\AdminBranchController;
use App\Http\Controllers\dashboard\admin\AdminContactController;
use App\Http\Controllers\dashboard\admin\AdminSocialMediaController;
use App\Http\Controllers\dashboard\admin\AdminCourseController;
use App\Http\Controllers\dashboard\admin\AdminSubjectController;
use App\Http\Controllers\dashboard\admin\AdminStudentEnrollController;
use App\Http\Controllers\dashboard\admin\AdminOfflineCourseController;
use App\Http\Controllers\dashboard\admin\AdminCompanyInfoController;
use App\Http\Controllers\dashboard\admin\AdminQuicklinkController;
use App\Http\Controllers\dashboard\admin\AdminWebBannerController;
use App\Http\Controllers\dashboard\admin\AdminFooterController;
use App\Http\Controllers\dashboard\admin\AdminOfflineEnrollController;
use App\Http\Controllers\dashboard\admin\AdminOfflineSubjectController;
use App\Http\Controllers\dashboard\admin\AdminPlacementController;
use App\Http\Controllers\dashboard\admin\AdminEventController;
use App\Http\Controllers\dashboard\admin\AdminEventMediaController;
use App\Http\Controllers\dashboard\admin\AdminStudentTestimonialController;
use App\Http\Middleware\PreventBackHistory;
use Illuminate\Support\Facades\Route;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;

// Public Route
Route::get('/', function () {
    return view('welcome');
});

// ====================== ADMIN DASHBOARD ROUTES ======================

// Authentication Routes (Public)
Route::prefix('admin')->group(function () {
    Route::get('/', [AuthController::class, 'loginPage'])->name('admin.login.page');

    // Throttled login to prevent brute-force attacks (max 10 attempts per minute)
    Route::middleware(['throttle:10,1'])->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('admin.login');

    });
});
Route::get('/login', function () {
    return redirect()->route('admin.login.page'); // Redirect to admin login
})->name('login');

// Protected Admin Routes (Require Authentication)
Route::middleware(['auth:admin','web', PreventBackHistory::class])->prefix('admin')->group(function () {
    // Dashboard & Profile
    Route::get('/dashboard', [AuthController::class,'dashboard'])->name('admin.dashboard');
    Route::get('/analytics', [HomeController::class, 'analytics'])->name('admin.analytics');
    Route::get('/profile', [IndexController::class, 'adminProfile'])->name('admin.profile');
    Route::post('/profile/update/{id}', [IndexController::class, 'adminUpdate'])->name('admin.profile.update');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    // ====================== User Management ======================
    //user
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/add', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/store', [UserController::class, 'store'])->name('admin.users.store');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('admin.users.update');
        Route::post('/delete/{id}', [UserController::class, 'destroy'])->name('admin.users.delete');
    });

    // role
    Route::prefix('roles')->group(function () {
        Route::get('/', [AdminRoleController::class, 'index'])->name('admin.roles.index');
        Route::get('/add', [AdminRoleController::class, 'create'])->name('admin.roles.create');
        Route::get('/edit/{id}', [AdminRoleController::class, 'edit'])->name('admin.roles.edit');
        Route::post('/store', [AdminRoleController::class, 'store'])->name('admin.roles.store');
        Route::post('/update/{id}', [AdminRoleController::class, 'update'])->name('admin.roles.update');
        Route::post('/delete/{id}', [AdminRoleController::class, 'destroy'])->name('admin.roles.delete');
    });

    // ====================== Contact Management ======================
    Route::prefix('locations')->group(function () {
        // Campuses
        Route::prefix('campuses')->group(function () {
            Route::get('/', [AdminCampusController::class, 'index'])->name('admin.campuses.index');
            Route::get('/add', [AdminCampusController::class, 'create'])->name('admin.campuses.create');
            Route::post('/store', [AdminCampusController::class, 'store'])->name('admin.campuses.store');
            Route::post('/update/{id}', [AdminCampusController::class, 'update'])->name('admin.campuses.update');
            Route::post('/delete/{id}', [AdminCampusController::class, 'destroy'])->name('admin.campuses.delete');
        });

            Route::prefix('campus-facilities')->group(function () {
                Route::get('/', [AdminCampusfacilityController::class, 'index'])->name('admin.campusfacilities.index');
                Route::get('/add', [AdminCampusfacilityController::class, 'create'])->name('admin.campusfacilities.create');
                Route::post('/store', [AdminCampusfacilityController::class, 'store'])->name('admin.campus.facilities.store');
                Route::post('/update/{id}', [AdminCampusfacilityController::class, 'update'])->name('admin.campusfacilities.update');
                Route::post('/delete/{id}', [AdminCampusfacilityController::class, 'destroy'])->name('admin.campusfacilities.delete');
            });
        // Branches
        Route::prefix('branches')->group(function () {
            Route::get('/', [AdminBranchController::class, 'index'])->name('admin.branches.index');
            Route::get('/add', [AdminBranchController::class, 'create'])->name('admin.branches.create');
            Route::post('/store', [AdminBranchController::class, 'store'])->name('admin.branches.store');
            Route::post('/update/{id}', [AdminBranchController::class, 'update'])->name('admin.branches.update');
            Route::post('/delete/{id}', [AdminBranchController::class, 'destroy'])->name('admin.branches.delete');
        });

        // Contacts
        Route::prefix('contacts')->group(function () {
            Route::get('/', [AdminContactController::class, 'index'])->name('admin.contacts.index');
            Route::get('/add', [AdminContactController::class, 'create'])->name('admin.contacts.create');
            Route::post('/store', [AdminContactController::class, 'store'])->name('admin.contacts.store');
            Route::post('/update/{id}', [AdminContactController::class, 'update'])->name('admin.contacts.update');
            Route::post('/delete/{id}', [AdminContactController::class, 'destroy'])->name('admin.contacts.delete');
        });
    });

    //socialmedia
    Route::prefix('socialmedia')->group(function () {
        Route::get('/', [AdminSocialMediaController::class, 'index'])->name('admin.socialmedia.index');
        Route::get('/add', [AdminSocialMediaController::class, 'create'])->name('admin.socialmedia.create');
        Route::post('/store', [AdminSocialMediaController::class, 'store'])->name('admin.socialmedia.store');
        Route::post('/update/{id}', [AdminSocialMediaController::class, 'update'])->name('admin.socialmedia.update');
        Route::post('/delete/{id}', [AdminSocialMediaController::class, 'destroy'])->name('admin.socialmedia.delete');
    });

    // ====================== Course Management ======================
    //Online-Courses
    Route::prefix('online-courses')->group(function () {

        //Course
        Route::prefix('courses')->group(function () {
            Route::get('/', [AdminCourseController::class, 'index'])->name('admin.courses.index');
            Route::get('/add', [AdminCourseController::class, 'create'])->name('admin.course.create');
            Route::post('/store', [AdminCourseController::class, 'store'])->name('admin.course.store');
            Route::post('/update/{id}', [AdminCourseController::class, 'update'])->name('admin.course.update');
            Route::post('/delete/{id}', [AdminCourseController::class, 'destroy'])->name('admin.course.delete');
        });

        //Subject
        Route::prefix('subjects')->group(function () {
            Route::get('/', [AdminSubjectController::class, 'index'])->name('admin.subjects.index');
            Route::get('/add', [AdminSubjectController::class, 'create'])->name('admin.subject.create');
            Route::post('/store', [AdminSubjectController::class, 'store'])->name('admin.subject.store');
            Route::post('/update/{id}', [AdminSubjectController::class, 'update'])->name('admin.subject.update');
            Route::post('/delete/{id}', [AdminSubjectController::class, 'destroy'])->name('admin.subject.delete');
        });

        //Student Enroll
        Route::prefix('student-enroll')->group(function () {
            Route::get('/', [AdminStudentEnrollController::class, 'index'])->name('admin.student.enroll.index');
            Route::get('/add', [AdminStudentEnrollController::class, 'create'])->name('admin.student.enroll.create');
            Route::post('/store', [AdminStudentEnrollController::class, 'store'])->name('admin.student.enroll.store');
            Route::post('/update/{id}', [AdminStudentEnrollController::class, 'update'])->name('admin.student.enroll.update');
            Route::post('/delete/{id}', [AdminStudentEnrollController::class, 'destroy'])->name('admin.student.enroll.delete');
            Route::get('/admin/student/enroll/filter', [AdminStudentEnrollController::class, 'filter'])->name('admin.student.enroll.filter');
            Route::get('/export-students', function () {
                return Excel::download(new StudentsExport, 'students.xlsx');
            })->name('students.export');
        });
        Route::post('/admin/student/import', [AdminStudentEnrollController::class, 'import'])->name('admin.student.import');

    });

    //Offline-Courses
    Route::prefix('offline-courses')->group(function () {

        //OfflineCourse
        Route::prefix('courses')->group(function () {
            Route::get('/', [AdminOfflineCourseController::class, 'index'])->name('admin.offline.courses.index');
            Route::get('/add', [AdminOfflineCourseController::class, 'create'])->name('admin.offline.course.create');
            Route::post('/store', [AdminOfflineCourseController::class, 'store'])->name('admin.offline.course.store');
            Route::post('/update/{id}', [AdminOfflineCourseController::class, 'update'])->name('admin.offline.course.update');
            Route::post('/delete/{id}', [AdminOfflineCourseController::class, 'destroy'])->name('admin.offline.course.delete');
        });

        //Subject
        Route::prefix('subjects')->group(function () {
            Route::get('/', [AdminOfflineSubjectController::class, 'index'])->name('admin.offline.subjects.index');
            Route::get('/add', [AdminOfflineSubjectController::class, 'create'])->name('admin.offline.subject.create');
            Route::post('/store', [AdminOfflineSubjectController::class, 'store'])->name('admin.offline.subject.store');
            Route::post('/update/{id}', [AdminOfflineSubjectController::class, 'update'])->name('admin.offline.subject.update');
            Route::post('/delete/{id}', [AdminOfflineSubjectController::class, 'destroy'])->name('admin.offline.subject.delete');
        });
        

        //Student Enroll
        Route::prefix('student-enroll')->group(function () {
            Route::get('/', [AdminOfflineEnrollController::class, 'index'])->name('admin.offline.student.enroll.index');
            Route::get('/add', [AdminOfflineEnrollController::class, 'create'])->name('admin.offline.student.enroll.create');
            Route::post('/store', [AdminOfflineEnrollController::class, 'store'])->name('admin.offline.student.enroll.store');
            Route::post('/update/{id}', [AdminOfflineEnrollController::class, 'update'])->name('admin.offline.student.enroll.update');
            Route::post('/delete/{id}', [AdminOfflineEnrollController::class, 'destroy'])->name('admin.offline.student.enroll.delete');
            Route::post('/admin/offline-student/import', [AdminOfflineEnrollController::class, 'import'])->name('admin.offline.student.import');
        });

    });

    // ====================== Academy Management ======================
    Route::prefix('adademy')->group(function () {

        //companyinfo
        Route::prefix('companyinfo')->group(function () {
            Route::get('/', [AdminCompanyInfoController::class, 'index'])->name('admin.company.infos.index');
            Route::get('/add', [AdminCompanyInfoController::class, 'create'])->name('admin.company.info.create');
            Route::post('/store', [AdminCompanyInfoController::class, 'store'])->name('admin.company.info.store');
            Route::post('/update/{id}', [AdminCompanyInfoController::class, 'update'])->name('admin.company.info.update');
            Route::post('/delete/{id}', [AdminCompanyInfoController::class, 'destroy'])->name('admin.company.info.delete');
        });

        //quicklink
        Route::prefix('quicklink')->group(function () {
            Route::get('/', [AdminQuicklinkController::class, 'index'])->name('admin.quicklinks.index');
            Route::get('/add', [AdminQuicklinkController::class, 'create'])->name('admin.quicklink.create');
            Route::post('/store', [AdminQuicklinkController::class, 'store'])->name('admin.quicklink.store');
            Route::post('/update/{id}', [AdminQuicklinkController::class, 'update'])->name('admin.quicklink.update');
            Route::post('/delete/{id}', [AdminQuicklinkController::class, 'destroy'])->name('admin.quicklink.delete');
        });

        //webbanner
        Route::prefix('web-banner')->group(function () {
            Route::get('/', [AdminWebBannerController::class, 'index'])->name('admin.web.banners.index');
            Route::get('/add', [AdminWebBannerController::class, 'create'])->name('admin.web.banner.create');
            Route::post('/store', [AdminWebBannerController::class, 'store'])->name('admin.web.banner.store');
            Route::post('/update/{id}', [AdminWebBannerController::class, 'update'])->name('admin.web.banner.update');
            Route::post('/delete/{id}', [AdminWebBannerController::class, 'destroy'])->name('admin.web.banner.delete');
        });
        
        //footer
        Route::prefix('footer')->group(function () {
            Route::get('/', [AdminFooterController::class, 'index'])->name('admin.footer.index');
            Route::get('/add', [AdminFooterController::class, 'create'])->name('admin.footer.create');
            Route::post('/store', [AdminFooterController::class, 'store'])->name('admin.footer.store');
            Route::post('/update/{id}', [AdminFooterController::class, 'update'])->name('admin.footer.update');
            Route::post('/delete/{id}', [AdminFooterController::class, 'destroy'])->name('admin.footer.delete');
        });
        
    });

    // ====================== Student Management ======================
    Route::prefix('student')->group(function () {

        //Placement
        Route::prefix('placement')->group(function () {
            Route::get('/', [AdminPlacementController::class, 'index'])->name('admin.placement.index');
            Route::get('/add', [AdminPlacementController::class, 'create'])->name('admin.placement.create');
            Route::post('/store', [AdminPlacementController::class, 'store'])->name('admin.placement.store');
            Route::post('/update/{id}', [AdminPlacementController::class, 'update'])->name('admin.placement.update');
            Route::post('/delete/{id}', [AdminPlacementController::class, 'destroy'])->name('admin.placement.delete');
        });

        Route::prefix('testimonials')->group(function () {
            Route::get('/', [AdminStudentTestimonialController::class, 'index'])->name('admin.testimonials.index');
            Route::get('/add', [AdminStudentTestimonialController::class, 'create'])->name('admin.testimonials.create');
            Route::post('/store', [AdminStudentTestimonialController::class, 'store'])->name('admin.testimonials.store');
            Route::post('/update/{id}', [AdminStudentTestimonialController::class, 'update'])->name('admin.testimonials.update');
            Route::post('/delete/{id}', [AdminStudentTestimonialController::class, 'destroy'])->name('admin.testimonials.delete');
        });

    });

    //events
    Route::prefix('event')->group(function () {
        Route::get('/', [AdminEventController::class, 'index'])->name('admin.events.index');
        Route::get('/add', [AdminEventController::class, 'create'])->name('admin.event.create');
        Route::post('/store', [AdminEventController::class, 'store'])->name('admin.event.store');
        Route::post('/update/{id}', [AdminEventController::class, 'update'])->name('admin.event.update');
        Route::post('/delete/{id}', [AdminEventController::class, 'destroy'])->name('admin.event.delete');
        Route::get('/view/{id}', [AdminEventController::class, 'show'])->name('admin.event.view');

        //event-media
        Route::prefix('event-media')->group(function () {
            Route::post('event/store', [AdminEventMediaController::class, 'store'])->name('admin.event.media.store');
            Route::post('/delete/{id}', [AdminEventMediaController::class, 'destroy'])->name('admin.event.media.delete');
        });
    });
    
});
require base_path('routes/website.php');