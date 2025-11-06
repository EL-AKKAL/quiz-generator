<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizRequest;
use App\Services\PictureService;
use App\Services\QuizService;

class QuizController extends Controller
{

    public function index()
    {
        $quizzes = user()->quizzes()->withCount('questions')->orderBy('created_at', 'desc')->paginate(6);

        return inertia('Quizzes/Index', [
            'quizzes' => $quizzes,
        ]);
    }

    public function create()
    {
        return inertia('Quizzes/Edit', [
            'quiz' => new \App\Models\Quiz(),
        ]);
    }

    public function store(QuizRequest $request)
    {
        QuizService::save(null, $request->validated(), $request->file('picture'));

        return redirect()->route('quizzes.index');
    }

    public function show(string $id)
    {
        $quiz = user()->quizzes()->with('questions')->findOrFail($id);

        return inertia('Quizzes/Edit', ['quiz' => $quiz]);
    }

    public function edit(string $id)
    {
        $quiz = user()->quizzes()->with('questions')->findOrFail($id);

        return inertia('Quizzes/Edit', [
            'quiz' => $quiz,
        ]);
    }

    public function update(QuizRequest $request, string $id)
    {
        $quiz = user()->quizzes()->findOrFail($id);

        QuizService::save($quiz, $request->validated(), $request->file('picture'));

        return redirect()->route('quizzes.index');
    }

    public function destroy(string $id)
    {
        $quiz = user()->quizzes()->findOrFail($id);

        PictureService::delete($quiz->picture);

        $quiz->questions()->delete();

        $quiz->delete();

        return redirect()->route('quizzes.index');
    }
}
