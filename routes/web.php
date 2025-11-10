<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\QuizController;

Route::redirect('/', '/dashboard')->name('home');

Route::middleware('auth')->group(function () {

    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('quizzes', QuizController::class);
});

Route::get('/view/{quiz:slug}', [QuizController::class, 'view'])->name('quizzes.view');
Route::post('/view/{quiz:slug}', [QuizController::class, 'save_answers'])->name('quizzes.save_answers');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
