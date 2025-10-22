<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\QuizController;

Route::redirect('/', '/dashboard');

Route::middleware('auth')->group(function () {

    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::controller(QuizController::class)->name('quiz.')->group(function () {
        Route::get('/quizzes', 'index')->name('index');
        Route::get('quiz/{quiz}', 'show')->name('show');
        Route::post('quiz', 'store')->name('store');
    });
});





require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
