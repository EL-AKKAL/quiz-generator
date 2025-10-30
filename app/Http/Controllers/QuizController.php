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
        $quiz = auth()->user()->quizzes()->with('questions')->find($id);
        return inertia('Quizzes/Edit', [
            'quiz' => $quiz,
        ]);
    }


    public function update(Request $request, string $id)
    {
        try {
            if (is_string($request->questions)) {
                $request->merge([
                    'questions' => json_decode($request->questions, true),
                ]);
            }
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255',
                'description' => 'nullable|string',
                'expire_date' => 'nullable|date',
                'status' => 'required|in:on,off,1,0,true,false',
                'picture' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'questions.*.id' => 'required|integer',
                'questions.*.question' => 'required|string|max:255',
                'questions.*.type' => 'required|string|in:text,select,radio',
                'questions.*.data' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (!is_array($value) && !is_string($value)) {
                            $fail("The {$attribute} field must be an array or a string.");
                        }
                    },
                ],
                'questions.*.data.options' => 'required_if:questions.*.type,select,radio|array',
                'questions.*.data.options.*.id' => 'required|integer|exists:options,id',
                'questions.*.data.options.*.value' => 'required|string|max:255',
            ]);

            $quiz = auth()->user()->quizzes()->find($id);
            $validated['status'] = in_array($validated['status'], ['on', '1', 'true']);
            if ($request->hasFile('picture')) {
                if ($quiz->picture)
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($quiz->picture);

                $validated['picture'] = $request->file('picture')->store('quizzes', 'public');
            }
            foreach ($validated['questions'] as $question) {
                $quiz->questions()->updateOrCreate(
                    ['id' => $question['id']],
                    $question
                );
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
