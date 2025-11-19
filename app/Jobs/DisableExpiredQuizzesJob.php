<?php

namespace App\Jobs;

use App\Models\Quiz;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class DisableExpiredQuizzesJob implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
    }

    public function handle(): void
    {
        $updated = Quiz::whereNotNull('expire_date')
            ->whereDate('expire_date', '<', now()->toDateString())
            ->where('status', true)
            ->update(['status' => false]);

        Log::info("Disabled {$updated} expired quizzes.");
    }
}
