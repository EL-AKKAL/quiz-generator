<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard')->name('home');

Route::middleware('auth')->group(function () {

    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::resource('quizzes', QuizController::class);

    Route::post('notifications/{id}/read', [QuizController::class, 'read'])->name('quizzes.read');
});

Route::get('/view/{quiz:slug}', [QuizController::class, 'view'])->name('quizzes.view');
Route::post('/view/{quiz:slug}', [QuizController::class, 'saveAnswers'])->name('quizzes.save_answers');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
