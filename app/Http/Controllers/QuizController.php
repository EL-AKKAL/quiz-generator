<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuizController extends Controller
{

    public function index()
    {
        $quizzes = auth()->user()->quizzes()->withCount('questions')->get();

        return inertia('Quizzes/Index', [
            'quizzes' => $quizzes,
        ]);
    }

    public function create() {}


    public function store(Request $request) {}

    public function show(string $id) {}

    public function edit(string $id) {}


    public function update(Request $request, string $id) {}

    public function destroy(string $id) {}
}
