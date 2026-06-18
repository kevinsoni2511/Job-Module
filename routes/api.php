<?php

use Illuminate\Support\Facades\Route;

// Auth
use App\Http\Controllers\API\V1\AuthController;

// Public
use App\Http\Controllers\API\V1\JobController;
use App\Http\Controllers\API\V1\JobCategoryController;
use App\Http\Controllers\API\V1\CompanyController;

// Admin
use App\Http\Controllers\API\V1\Admin\JobApplicationController as AdminJobApp;
use App\Http\Controllers\API\V1\Admin\UserController;
use App\Http\Controllers\API\V1\Admin\JobPostController as AdminJobPost;
use App\Http\Controllers\API\V1\Admin\JobCategoryController as AdminCategory;
use App\Http\Controllers\API\V1\Admin\CompanyController as AdminCompany;
use App\Http\Controllers\API\V1\Admin\DashboardController as AdminDashboard;

// Job Keeper
use App\Http\Controllers\API\V1\JobKeeper\DashboardController as JKDashboard;
use App\Http\Controllers\API\V1\JobKeeper\CompanyController as JKCompany;
use App\Http\Controllers\API\V1\JobKeeper\JobPostController;
use App\Http\Controllers\API\V1\JobKeeper\JobApplicationController as JKJobApp;

// Job Seeker
use App\Http\Controllers\API\V1\JobSeeker\DashboardController as JSDashboard;
use App\Http\Controllers\API\V1\JobSeeker\ProfileController;
use App\Http\Controllers\API\V1\JobSeeker\JobSearchController;
use App\Http\Controllers\API\V1\JobSeeker\JobApplicationController as JSJobApp;
use App\Http\Controllers\API\V1\JobSeeker\SavedJobController;
use App\Http\Controllers\API\V1\JobSeeker\JobShareController;
use App\Http\Controllers\API\V1\JobSeeker\EducationController;
use App\Http\Controllers\API\V1\JobSeeker\ExperienceController;

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | AUTH – Public
    |--------------------------------------------------------------------------
    */
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);

    /*
    |--------------------------------------------------------------------------
    | PUBLIC APIs
    |--------------------------------------------------------------------------
    */

    // Jobs — browse & search via query param: ?keyword=laravel&city=ahmedabad
    Route::get('jobs',       [JobController::class, 'index']);
    Route::get('jobs/{id}',  [JobController::class, 'show']);

    // Categories
    Route::get('job-categories',       [JobCategoryController::class, 'index']);
    Route::get('job-categories/{id}',  [JobCategoryController::class, 'show']);

    // Companies
    Route::get('companies',       [CompanyController::class, 'index']);
    Route::get('companies/{id}',  [CompanyController::class, 'show']);

    /*
    |--------------------------------------------------------------------------
    | AUTHENTICATED
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth:sanctum')->group(function () {

        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me',      [AuthController::class, 'me']);

        /*
        |----------------------------------------------------------------------
        | ADMIN  →  middleware: admin
        |----------------------------------------------------------------------
        */
        Route::prefix('admin')->middleware('admin')->group(function () {

            Route::get('dashboard', [AdminDashboard::class, 'index']);

            Route::apiResource('users',      UserController::class);
            Route::apiResource('jobs',       AdminJobPost::class);
            Route::apiResource('categories', AdminCategory::class);
            Route::apiResource('companies',  AdminCompany::class);

            // Company status update
            Route::patch('companies/{id}/status', [AdminCompany::class, 'updateStatus']);

            // Applications
            Route::get('applications',        [AdminJobApp::class, 'index']);
            Route::get('applications/{id}',   [AdminJobApp::class, 'show']);
            Route::delete('applications/{id}',[AdminJobApp::class, 'destroy']);
        });

        /*
        |----------------------------------------------------------------------
        | JOB KEEPER  →  middleware: job_keeper
        |----------------------------------------------------------------------
        */
        Route::prefix('job-keeper')->middleware('job_keeper')->group(function () {

            Route::get('dashboard', [JKDashboard::class, 'index']);

            // Company
            Route::post('company', [JKCompany::class, 'store']);
            Route::get('company',  [JKCompany::class, 'show']);
            Route::put('company',  [JKCompany::class, 'update']);

            // Job Posts
            Route::apiResource('jobs', JobPostController::class);
            Route::patch('jobs/{id}/toggle-status', [JobPostController::class, 'toggleStatus']);

            // Applications
            Route::get('jobs/{jobPostId}/applications',                    [JKJobApp::class, 'index']);
            Route::get('jobs/{jobPostId}/applications/summary',            [JKJobApp::class, 'summary']);
            Route::get('jobs/{jobPostId}/applications/{id}',               [JKJobApp::class, 'show']);
            Route::patch('jobs/{jobPostId}/applications/{id}/status',      [JKJobApp::class, 'updateStatus']);
        });

        /*
        |----------------------------------------------------------------------
        | JOB SEEKER  →  middleware: job_seeker
        |----------------------------------------------------------------------
        */
        Route::prefix('job-seeker')->middleware('job_seeker')->group(function () {

            Route::get('dashboard', [JSDashboard::class, 'index']);

            // Profile
            Route::post('profile', [ProfileController::class, 'store']);
            Route::get('profile',  [ProfileController::class, 'show']);
            Route::put('profile',  [ProfileController::class, 'update']);

            // Job Search — ?keyword=laravel&city=ahmedabad&job_type=full-time
            Route::get('jobs',        [JobSearchController::class, 'index']);
            Route::get('jobs/{slug}', [JobSearchController::class, 'show']);

            // Job Share
            Route::get('jobs/{id}/share', [JobShareController::class, 'share']);

            // Applications
            Route::post('jobs/{jobPostId}/apply',       [JSJobApp::class, 'apply']);
            Route::get('applications',                  [JSJobApp::class, 'index']);
            Route::get('applications/{id}',             [JSJobApp::class, 'show']);
            Route::patch('applications/{id}/withdraw',  [JSJobApp::class, 'withdraw']);

            // Saved Jobs
            Route::post('jobs/{jobPostId}/save', [SavedJobController::class, 'toggle']);
            Route::get('saved-jobs',             [SavedJobController::class, 'index']);

            // Education
            Route::get('education',        [EducationController::class, 'index']);
            Route::post('education',       [EducationController::class, 'store']);
            Route::put('education/{id}',   [EducationController::class, 'update']);
            Route::delete('education/{id}',[EducationController::class, 'destroy']);

            // Experience
            Route::get('experience',        [ExperienceController::class, 'index']);
            Route::post('experience',       [ExperienceController::class, 'store']);
            Route::put('experience/{id}',   [ExperienceController::class, 'update']);
            Route::delete('experience/{id}',[ExperienceController::class, 'destroy']);
        });
    });
});