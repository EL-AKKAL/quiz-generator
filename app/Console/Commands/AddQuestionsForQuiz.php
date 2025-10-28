<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use function Pest\Laravel\json;

class AddQuestionsForQuiz extends Command
{


    protected $signature = 'app:add-questions-for-quiz {quizID?} {count?}';

    protected $description = 'Add questions to a specified quiz';

    public function handle()
    {
        try {
            $quizID = $this->argument('quizID') ?? $this->ask('Enter quiz ID (default is 1)', '1');
            $count = $this->argument('count') ?? $this->ask('How many questions to create ? (default is 10)', '10');

            $quiz = \App\Models\Quiz::find((int) $quizID);
            if (!$quiz) {
                $this->error("Quiz with ID {$quizID} doesn't exist");
                return 1;
            }

            $this->info("Creating {$count} questions for quiz #{$quiz->id} ({$quiz->name})...");

            DB::beginTransaction();

            for ($i = 0; $i < $count; $i++) {
                $type = ['select', 'radio', 'text'][array_rand(['select', 'radio', 'text'])];
                $question = new \App\Models\Question([
                    'quiz_id' => $quiz->id,
                    'question' => $this->generateRandomQuestionText(),
                    'type' => $type,
                    //generate data depending on type
                    'data' => match ($type) {
                        'select', 'radio' => [
                            'options' => collect(['A', 'B', 'C', 'D'])
                                ->map(fn($letter) => ['text' => "Option {$letter}"])
                                ->shuffle()
                                ->values(),
                        ],
                        'text' => $this->generateRandomAnswerText(),
                    },
                ]);
                $question->save();
            }

            DB::commit();
            $this->info("Successfully created {$count} questions for quiz #{$quiz->id} ({$quiz->name})");
            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            DB::rollBack();
            return self::FAILURE;
        }

    }

    private function generateRandomQuestionText()
    {
        $questions = [
            // 🧠 PHP Basics
            'What is the difference between == and === in PHP?',
            'Which PHP function is used to count elements in an array?',
            'What will `echo (int)(10.7)` output?',
            'How can you check if a variable is an array in PHP?',
            'What is the difference between require and include in PHP?',
            'What does the function `var_dump()` do?',

            // ⚙️ Laravel Core
            'What Artisan command is used to clear all caches?',
            'How do you define a route that accepts an optional parameter in Laravel?',
            'What is the purpose of a Service Provider in Laravel?',
            'What method is used to create a new Eloquent model instance and save it?',
            'What is the difference between `first()` and `find()` in Eloquent?',
            'What is a migration in Laravel and why is it important?',
            'What is the use of middleware in Laravel?',

            // 💾 Database / Eloquent
            'What is the difference between one-to-many and many-to-many relationships?',
            'How can you eager-load relationships in Laravel?',
            'What will the following query return: `User::whereNull("email_verified_at")->count();`?',
            'How do you rollback the last database migration in Laravel?',

            // 🌐 Frontend & General Web
            'What is the difference between GET and POST methods in HTTP?',
            'What does the `<meta charset="UTF-8">` tag do in HTML?',
            'What does CSS stand for?',
            'What is the difference between localStorage and sessionStorage in JavaScript?',

            // 🔒 Security & Misc
            'What is CSRF protection and how does Laravel handle it?',
            'What does hashing passwords mean and why is it necessary?',
            'What is the purpose of the `.env` file in Laravel?',
            'What is an API and what does REST stand for?',
        ];

        return $questions[array_rand($questions)];
    }

    private function generateRandomAnswerText()
    {
        $answers = [
            '42',
            'Laravel is a PHP framework.',
            'Use the `count()` function.',
            'It improves security by hashing passwords.',
            'You can use `Storage::disk("public")->put("file.txt", $content);`',
            'Eloquent is Laravel\'s ORM for database interactions.',
            'You can use `php artisan migrate:rollback` to rollback the last migration.',
            'CSRF protection prevents cross-site request forgery attacks.',
            'The `.env` file stores environment-specific configuration settings.',
            'REST stands for Representational State Transfer.',
        ];

        return $answers[array_rand($answers)];
    }

}
