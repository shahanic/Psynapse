<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Exams
    Route::get('/exams', [App\Http\Controllers\ExamController::class, 'index'])->name('exams.index');
    Route::get('/exams/upload', [App\Http\Controllers\ExamController::class, 'create'])->name('exams.create');
    Route::post('/exams', [App\Http\Controllers\ExamController::class, 'store'])->name('exams.store');
    Route::get('/exams/{exam}/retry', [App\Http\Controllers\ExamController::class, 'retry'])->name('exams.retry');
    Route::get('/exams/{exam}', [App\Http\Controllers\ExamController::class, 'show'])->name('exams.show');
    Route::delete('/exams/{exam}', [App\Http\Controllers\ExamController::class, 'destroy'])->name('exams.destroy');

    // Review
    Route::get('/review/{exam}/mcq', [App\Http\Controllers\ReviewController::class, 'mcq'])->name('review.mcq');
    Route::post('/review/{exam}/mcq', [App\Http\Controllers\ReviewController::class, 'submitMcq'])->name('review.mcq.submit');
    Route::get('/review/{exam}/flashcard', [App\Http\Controllers\ReviewController::class, 'flashcard'])->name('review.flashcard');
    Route::get('/review/{exam}/mock', [App\Http\Controllers\ReviewController::class, 'mock'])->name('review.mock');
    Route::post('/review/{exam}/mock', [App\Http\Controllers\ReviewController::class, 'submitMock'])->name('review.mock.submit');
});

// Admin
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
});

require __DIR__.'/auth.php';