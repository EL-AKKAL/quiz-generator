<?php

namespace App\Http\Controllers;

use App\Models\Answer;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $totalQuizzes = user()->quizzes()->count();

        $totalActiveQuizzes = user()->quizzes()->where('status', true)->count();

        $latestQuizzes = user()->quizzes()->latest()->take(1)->get();

        $totalAnswers = Answer::whereIn('quiz_id', user()->quizzes()->pluck('id'))->count();

        $latestAnswers = Answer::whereIn('quiz_id', user()->quizzes()->pluck('id'))->orderBy('end_date', 'desc')->take(5)->get();

        return inertia('Dashboard', [
            'totalQuizzes' => $totalQuizzes,
            'totalActiveQuizzes' => $totalActiveQuizzes,
            'latestQuiz' => $latestQuizzes,
            'totalAnswers' => $totalAnswers,
            'latestAnswers' => $latestAnswers,
        ]);
    }
}
