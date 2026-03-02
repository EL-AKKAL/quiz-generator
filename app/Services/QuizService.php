<?php

namespace App\Services;

use App\Models\Quiz;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class QuizService
{
    public static function save(?Quiz $quiz, array $validated, $file = null): Quiz
    {
        return DB::transaction(function () use ($quiz, $validated, $file) {
            $oldPicture = $quiz?->picture ?? null;

            $pictureState = $validated['picture_state'] ?? null;

            $validated['picture'] = self::handlePicture(
                file: $file,
                oldPath: $oldPicture,
                pictureState: $pictureState,
                directory: 'quizzes'
            );

            unset($validated['picture_state']);

            if (! $quiz) {
                $quiz = user()->quizzes()->create($validated);
            } else {
                $quiz->update($validated);
            }

            QuestionsService::sync($quiz, $validated['questions'] ?? []);

            return $quiz;
        });
    }

    protected static function handlePicture(?UploadedFile $file, ?string $oldPath, ?string $pictureState, string $directory): ?string
    {
        if ($pictureState === 'removed') {
            if ($oldPath) {
                PictureService::delete($oldPath);
            }

            return null;
        }

        if ($pictureState === 'existing') {
            return $oldPath;
        }

        return PictureService::store($file, $oldPath, $directory);
    }
}
