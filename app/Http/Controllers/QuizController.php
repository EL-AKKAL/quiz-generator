<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuizController extends Controller
{

    public function index()
    {
        $quizzes = auth()->user()->quizzes()->withCount('questions')->orderBy('created_at', 'desc')->get();

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


    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255',
                'description' => 'nullable|string',
                'expire_date' => 'nullable|date',
                'status' => 'required|in:on,off,1,0,true,false',
                'picture' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            ]);

            $validated['status'] = in_array($validated['status'], ['on', '1', 'true']);

            if ($request->hasFile('picture')) {
                $validated['picture'] = $request->file('picture')->store('quizzes', 'public');
            }

            auth()->user()->quizzes()->create($validated);

            return redirect()->route('quizzes.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        }
    }

    public function show(string $id)
    {
        $quiz = auth()->user()->quizzes()->find($id);
        return inertia('Quizzes/Edit', [
            'quiz' => $quiz,
        ]);
    }

    public function edit(string $id)
    {
        $quiz = auth()->user()->quizzes()->find($id);
        return inertia('Quizzes/Edit', [
            'quiz' => $quiz,
        ]);
    }


    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255',
                'description' => 'nullable|string',
                'expire_date' => 'nullable|date',
                'status' => 'required|in:on,off,1,0,true,false',
                'picture' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            ]);

            $quiz = auth()->user()->quizzes()->find($id);
            $validated['status'] = in_array($validated['status'], ['on', '1', 'true']);
            if ($request->hasFile('picture')) {
                if ($quiz->picture)
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($quiz->picture);

                $validated['picture'] = $request->file('picture')->store('quizzes', 'public');
            }
            $quiz->update($validated);
            return redirect()->route('quizzes.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        }
    }

    public function destroy(string $id)
    {
        $quiz = auth()->user()->quizzes()->find($id);
        $quiz->delete();
        return redirect()->route('quizzes.index');
    }
}
