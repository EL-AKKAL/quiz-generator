<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateQuizzes extends Command
{
    protected $signature = 'app:generate-quizzes {userID?} {count?}';

    protected $description = 'create quizzes for a given user.';

    public function handle()
    {
        try {
            $userID = $this->argument('userID') ?? $this->ask('Enter user ID (default is 1)', '1');
            $count = $this->argument('count') ?? $this->ask('How many quizzes to create ? (default is 10)', '10');

            $user = \App\Models\User::find((int) $userID);

            if (! $user) {
                $this->error("User with ID {$userID} doesn't exist");

                return 1;
            }

            $this->info("Creating {$count} quizzes for user #{$user->id} ({$user->name})...");

            DB::beginTransaction();

            $this->withProgressBar(range(1, (int) $count), function () use ($user) {
                \App\Models\Quiz::factory()->create([
                    'user_id' => $user->id,
                ]);
            });

            DB::commit();

            $this->info('creating quizzes completed successfully.');

            return self::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error creating quizzes: '.$e->getMessage());

            return self::FAILURE;
        }
    }
}
