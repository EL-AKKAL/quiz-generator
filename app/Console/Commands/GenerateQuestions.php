<?php

namespace App\Console\Commands;

use App\Models\Quiz;
use App\QuestionType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenerateQuestions extends Command
{
    protected $signature = 'app:quiz:generate-questions {quizID?} {count?}';

    protected $description = 'Add questions to a specified quiz';

    public function handle()
    {
        try {
            $quizID = $this->argument('quizID') ?? $this->ask('Enter quiz ID (default is 1)', '1');
            $count = $this->argument('count') ?? $this->ask('How many questions to create ? (default is 10)', '10');

            $quiz = Quiz::find((int) $quizID);

            if (! $quiz) {
                $this->error("Quiz with ID {$quizID} doesn't exist");

                return 1;
            }

            $this->info("Creating {$count} questions for quiz #{$quiz->id} ({$quiz->name})...");

            DB::transaction(function () use ($quiz, $count) {
                DB::table('questions')->insert(
                    $this->generateQuestions($quiz->id, $count)
                );
            });

            $this->info("done created {$count} questions for quiz #{$quiz->id} ({$quiz->name})");

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('An error occurred: '.$e->getMessage());

            return self::FAILURE;
        }

    }

    private function generateQuestions(int $quizID, int $count): array
    {
        $time = now()->toDateTimeString();

        return collect(range(1, $count))
            ->map(fn () => $this->generateQuestionRow($quizID, $time))
            ->all();
    }

    private function generateQuestionRow(int $quizID, string $time): array
    {
        $type = QuestionType::random();

        return [
            'quiz_id' => $quizID,
            'question' => "Question for quiz $quizID: ".Str::random(10),
            'type' => $type->value,
            'data' => json_encode($this->generateQuestionData($type)),
            'created_at' => $time,
            'updated_at' => $time,
        ];
    }

    private function generateQuestionData(QuestionType $type): array|string
    {
        return $type->isChoiceBased()
            ? [
                'options' => collect(range(1, 4))
                    ->map(fn ($i) => [
                        'id' => (string) Str::uuid(),
                        'text' => "Option $i",
                    ])
                    ->all(),
            ]
            : 'this is a random generated answer';
    }
}
