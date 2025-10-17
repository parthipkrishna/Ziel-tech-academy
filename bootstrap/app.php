<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {

            // Web CMS routes
            Route::middleware('web')
                ->prefix('cms/mobile')
                ->group(base_path('routes/lms.php'));

            // Student routes
            Route::middleware('web')
                ->prefix('student')
                ->group(base_path('routes/student.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {

        // Redirect guests based on route
        $middleware->redirectGuestsTo(function (Request $request) {

            if ($request->is('admin/*') || $request->routeIs('admin.*')) {
                return route('admin.login');
            }

            if ($request->is('lms/*') || $request->routeIs('lms.*')) {
                return route('lms.login');
            }

            if ($request->is('student-home/*') || $request->routeIs('student.*')) {
                return route('student.login.page');
            }

            return route('lms.login');
        });

        // Append global middleware if needed
        // $middleware->append(\App\Http\Middleware\PreventBackHistory::class);

        // Append optional Sanctum middleware globally if you want it on all API/student routes
        $middleware->append(\App\Http\Middleware\OptionalSanctumAuth::class);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
