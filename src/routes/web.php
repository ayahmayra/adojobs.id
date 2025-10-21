<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\SeekerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Employer;
use App\Http\Controllers\Seeker;

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('/lowongan', [JobController::class, 'index'])->name('jobs.index');
Route::get('/lowongan/{job}', [JobController::class, 'show'])->name('jobs.show');
Route::get('/kategori', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/kategori/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/employers', [EmployerController::class, 'index'])->name('employers.index');
Route::get('/employers/{employer:slug}', [EmployerController::class, 'show'])->name('employers.show');
Route::get('/kandidat', [SeekerController::class, 'index'])->name('seekers.index');
Route::get('/kandidat/{seeker}', [SeekerController::class, 'show'])->name('seekers.show');
Route::get('/resume/{slug}', [ResumeController::class, 'show'])->name('resume.show');

// Article Routes
Route::get('/artikel', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/artikel/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');

// Dashboard Route - Redirect based on role
Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif (auth()->user()->isEmployer()) {
        return redirect()->route('employer.dashboard');
    } elseif (auth()->user()->isSeeker()) {
        return redirect()->route('seeker.dashboard');
    }
    
    // Fallback jika role tidak dikenali
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/seeker', [ProfileController::class, 'updateSeeker'])->name('profile.seeker.update');
    Route::patch('/profile/employer', [ProfileController::class, 'updateEmployer'])->name('profile.employer.update');
    Route::patch('/profile/employer/slug', [ProfileController::class, 'updateEmployerSlug'])->name('profile.employer.slug.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Messaging Routes (Shared by Seeker & Employer)
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('index');
        Route::get('/unread-count', [MessageController::class, 'unreadCount'])->name('unreadCount');
        Route::post('/start', [MessageController::class, 'startConversation'])->name('start');
        Route::get('/{conversation}', [MessageController::class, 'show'])->name('show');
        Route::post('/{conversation}', [MessageController::class, 'store'])->name('store');
        Route::delete('/{conversation}', [MessageController::class, 'destroy'])->name('destroy');
    });

    // Admin Routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', Admin\UserController::class);
        
        // Jobs Routes
        Route::resource('jobs', Admin\JobController::class);
        Route::patch('/jobs/{job}/toggle-featured', [Admin\JobController::class, 'toggleFeatured'])->name('jobs.toggle-featured');
        Route::patch('/jobs/{job}/update-status', [Admin\JobController::class, 'updateStatus'])->name('jobs.update-status');
        
        Route::resource('categories', Admin\CategoryController::class);
        Route::resource('articles', Admin\ArticleController::class);
    });

    // Employer Routes
    Route::middleware(['employer'])->prefix('employer')->name('employer.')->group(function () {
        Route::get('/dashboard', [Employer\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('jobs', Employer\JobController::class);
        
        // Applications
        Route::get('/applications', [Employer\ApplicationController::class, 'index'])
            ->name('applications.index');
        Route::get('/applications/{application}', [Employer\ApplicationController::class, 'show'])
            ->name('applications.show');
        Route::patch('/applications/{application}/status', [Employer\ApplicationController::class, 'updateStatus'])
            ->name('applications.updateStatus');
    });

    // Seeker Routes
    Route::middleware(['seeker'])->prefix('seeker')->name('seeker.')->group(function () {
        Route::get('/dashboard', [Seeker\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/jobs', [Seeker\DashboardController::class, 'browseJobs'])->name('jobs.index');
        Route::get('/jobs/{job}/apply', [Seeker\ApplicationController::class, 'create'])
            ->name('applications.create');
        Route::post('/jobs/{job}/apply', [Seeker\ApplicationController::class, 'store'])
            ->name('jobs.apply');
        Route::get('/applications', [Seeker\ApplicationController::class, 'index'])
            ->name('applications.index');
        Route::patch('/applications/{application}/withdraw', [Seeker\ApplicationController::class, 'withdraw'])
            ->name('applications.withdraw');
        
        // Saved Jobs
        Route::get('/saved-jobs', [Seeker\SavedJobController::class, 'index'])
            ->name('saved-jobs.index');
        Route::post('/jobs/{job}/toggle-save', [Seeker\SavedJobController::class, 'toggle'])
            ->name('jobs.toggle-save');
        Route::delete('/saved-jobs/{savedJob}', [Seeker\SavedJobController::class, 'destroy'])
            ->name('saved-jobs.destroy');
    });
});

require __DIR__.'/auth.php';
