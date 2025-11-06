<?php

namespace App\Services;

class QuestionsService
{
    public static function sync($quiz, array $questionsData = []): void
    {
        $newIds = collect($questionsData)->pluck('id')->filter()->toArray();
        $quiz->questions()->whereNotIn('id', $newIds)->delete();

        foreach ($questionsData as $data) {
            $quiz->questions()->updateOrCreate(
                ['id' => $data['id'] ?? null],
                [
                    'question' => $data['question'],
                    'type' => $data['type'],
                    'data' => $data['data'] ?? null,
                ]
            );
        }
    }
}
