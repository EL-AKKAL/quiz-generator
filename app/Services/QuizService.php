<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Quiz;

class QuizService
{
    public static function save(?Quiz $quiz, array $validated, $file = null): Quiz
    {
        return DB::transaction(function () use ($quiz, $validated, $file) {
            $oldPicture = $quiz?->picture ?? null;

            $validated['picture'] = PictureService::store(
                file: $file,
                oldPath: $oldPicture,
                directory: 'quizzes'
            );

            if (!$quiz)
                $quiz = user()->quizzes()->create($validated);
            else
                $quiz->update($validated);

            QuestionsService::sync($quiz, $validated['questions'] ?? []);

            return $quiz;
        });
    }
}
