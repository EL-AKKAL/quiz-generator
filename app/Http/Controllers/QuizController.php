<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuizRequest;
use App\Models\Question;
use App\Models\Quiz;
use App\Notifications\AnswerSubmittedNotification;
use App\Services\PictureService;
use App\Services\QuizService;
use Illuminate\Http\Request;

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

    public function saveAnswers(Request $request, Quiz $quiz)
    {
        if (! $quiz->status) {
            return redirect()->route('quizzes.view', $quiz->slug);
        }

        $answer = $quiz->answers()->create([
            'start_date' => now(),
            'end_date' => now(),
        ]);

        foreach ($request->all() as $questionId => $response) {
            if (empty($response)) {
                continue;
            }

            $question = Question::where(['id' => $questionId, 'quiz_id' => $quiz->id])->get();

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
            ];

            $answer->questionAnswers()->create($finalAnswer);

            user()->notify(new AnswerSubmittedNotification($quiz->id, $answer->id));
        }

        return redirect()->route('quizzes.view', $quiz->slug)
            ->with('success', 'Answers saved successfully!');
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
