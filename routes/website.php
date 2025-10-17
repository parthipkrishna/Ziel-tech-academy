<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\HomepageController;
use App\Http\Controllers\web\AboutController;
use App\Http\Controllers\web\ContactController;
use App\Http\Controllers\web\BranchController;
use App\Http\Controllers\web\PlacementController;
use App\Http\Controllers\web\CampusController;

// ====================== WEBSITE ROUTES ======================
Route::get('/', [HomepageController::class, 'index'])->name('home');
Route::post('/store', [HomepageController::class, 'store'])->name('online.store');
Route::get('/social-media', [HomepageController::class, 'showSocialMediaLinks']);
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/branch', [BranchController::class, 'index'])->name('branch');
Route::get('/placement', [PlacementController::class, 'index'])->name('placement');
Route::get('/online-courses', [CampusController::class, 'index'])->name('online-courses');
Route::post('/campus/store', [CampusController::class, 'store'])->name('offline.store');
Route::get('/mobile/terms-and-conditions', [HomepageController::class, 'terms'])->name('terms-and-conditions');
Route::get('/mobile/privacy-and-policy', [HomepageController::class, 'policy'])->name('privacy-and-policy');