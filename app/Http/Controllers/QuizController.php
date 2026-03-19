<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizRequest;
use App\Http\Requests\SubmitQuizRequest;
use App\Models\Quiz;
use App\Notifications\AnswerSubmittedNotification;
use App\Services\PictureService;
use App\Services\QuizService;
use App\Services\ScoringService;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    private const EDIT_QUIZ = 'Quizzes/Edit';

    public function index()
    {
        $quizzes = user()->quizzes()->withCount('questions')->orderBy('created_at', 'desc')->paginate(6);

        return inertia('Quizzes/Index', [
            'quizzes' => $quizzes,
        ]);
    }

    public function create()
    {
        return inertia(self::EDIT_QUIZ, [
            'quiz' => new Quiz,
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

        return inertia(self::EDIT_QUIZ, ['quiz' => $quiz]);
    }

    public function edit(string $id)
    {
        $quiz = user()->quizzes()->with('questions')->findOrFail($id);

        return inertia(self::EDIT_QUIZ, [
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

    public function view(Quiz $quiz)
    {
        if (! $quiz->status) {
            abort(404);
        }

        return inertia('Quizzes/View', [
            'quiz' => $quiz->load('questions'),
        ]);
    }

    public function saveAnswers(SubmitQuizRequest $request, Quiz $quiz)
    {
        if (! $quiz->status) {
            return redirect()->route('quizzes.view', $quiz->slug);
        }

        $createdAnswer = null;

        DB::transaction(function () use ($request, $quiz, &$createdAnswer) {
            $answer = $quiz->answers()->create([
                'start_date' => now(),
                'end_date' => now(),
                'user_email' => $request['user_email'],
            ]);

            $questions = $quiz->questions()->get()->keyBy('id');

            foreach ($request->input('answers') as $questionId => $response) {
                if ($questionId === 0 && $response === null) {
                    continue;
                }

                if ($response === null || $response === '' || (is_array($response) && empty($response))) {
                    return redirect()->back()
                        ->withErrors(['answer all the questions before submitting.']);
                }

                $question = $questions[$questionId] ?? null;

                if (! $question) {
                    return redirect()->route('quizzes.view', $quiz->id)
                        ->with('error', 'Invalid question ID.');
                }

                $finalAnswer = [
                    'question_id' => $questionId,
                    'answer_id' => $answer->id,
                    'answer' => is_array($response)
                        ? json_encode($response)
                        : $response,
                    'score' => ScoringService::calculate($question, $response),
                ];

                $answer->questionAnswers()->create($finalAnswer);
            }

            $finalScore = round($answer->questionAnswers()->avg('score'), 2);

            $answer->update([
                'score' => $finalScore ?? 0,
            ]);

            user()->notify(new AnswerSubmittedNotification($quiz->id, $answer->id));
            $createdAnswer = $answer->load('questionAnswers');
        });

        return inertia('Quizzes/View', [
            'quiz' => $quiz->load('questions'),
            'createdAnswer' => $createdAnswer,
            'success' => 'Answers saved successfully!',
        ]);
    }

    public function read($id)
    {
        $notification = user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return redirect()->back();
    }
}
