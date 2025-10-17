<?php

namespace App\Providers;
use App\Models\ContactInfo;
use App\Models\Notification;
use App\Models\SocialMediaLink;
use App\Models\Student;
use App\Models\Subscription;
use Laravel\Passport\Passport;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; 
use View;
use App\Models\StudentEnrollment;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        $defaultSocialLinks = [
            'facebook' => ['platform' => 'facebook', 'url' => 'https://www.facebook.com/profile.php?id=61574238955271'],
            'linkedin' => ['platform' => 'linkedin', 'url' => 'https://www.linkedin.com/company/zieltech-academy/'],
            'instagram' => ['platform' => 'instagram', 'url' => 'https://www.instagram.com/zieltech.academy/'],
            'twitter' => ['platform' => 'twitter', 'url' => 'https://x.com/ZieltechAcademy'],
            'youtube' => ['platform' => 'youtube', 'url' => 'https://youtube.com/@zieltech.academy?si=FHksNt0MsY-tNmtE'],
            'pinterest' => ['platform' => 'pinterest', 'url' => 'https://pin.it/7imQFzlTS']
        ];

        // ✅ Check if table exists before querying
        if (Schema::hasTable('social_media_links')) {
            $dbSocialLinks = SocialMediaLink::all()->keyBy('platform')->toArray();
            $socialLinks = array_merge($defaultSocialLinks, $dbSocialLinks);
        } else {
            $socialLinks = $defaultSocialLinks;
        }

        View::composer('web.layouts.layout', function ($view) use ($socialLinks) {
            $view->with('socialLinks', $socialLinks);
        });
        View::composer('*', function ($view) {
            $contact = ContactInfo::first();
            $view->with('contact', $contact);
        });

        View::composer('student.layouts.layout', function ($view) {
        if (Auth::check()) {
            $user = Auth::user();
            $student = \App\Models\Student::where('user_id', $user->id)->first();

            $subscriptions = $student
            ? Subscription::with('course')->where('student_id', $student->id)->get()
            : collect();

            $view->with([
            'subscriptions' => $subscriptions,
            'studentName' => $student ? $student->first_name : $user->name, 
            'profile_image' => $student->profile_photo,
        ]);
        }
    });
    
        View::composer('student.layouts.layout', function ($view) {
            $userId = Auth::id();

            $student = Student::where('user_id', $userId)->first();

            if (!$student) {
                $notifications = collect();
            } else {
                $studentId = $student->id;
                $batchId = $student->batch_id;

                $notifications = Notification::query()
                    ->where(function ($q) use ($userId, $studentId, $batchId) {
                        // Notifications specifically for the user
                        $q->where('user_id', $userId)

                        ->orWhere(function ($q2) use ($studentId) {
                            $q2->where('category_type', 'student')
                            ->whereJsonContains('student_ids', $studentId);
                        })

                        ->orWhere(function ($q3) use ($batchId) {
                            $q3->where('category_type', 'batch')
                            ->whereJsonContains('batch_ids', $batchId);
                        })

                        ->orWhere('category_type', 'general');
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            $view->with('notifications', $notifications);
        });
    }
}
