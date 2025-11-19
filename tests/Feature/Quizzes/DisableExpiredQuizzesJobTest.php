<?php

use App\Jobs\DisableExpiredQuizzesJob;
use App\Models\Quiz;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Artisan;

uses(RefreshDatabase::class);

describe('Jobs', function () {
    it('disable quizzes by job', function () {

        $user = actingUser();
        $expired = Quiz::factory()->create([
            'user_id' => $user->id,
            'expire_date' => now()->subDay(),
            'status' => true,
        ]);

        $active = Quiz::factory()->create([
            'user_id' => $user->id,
            'expire_date' => now()->addDay(),
            'status' => true,
        ]);

        $noExpire = Quiz::factory()->create([
            'user_id' => $user->id,
            'expire_date' => null,
            'status' => true,
        ]);

        (new DisableExpiredQuizzesJob())->handle();

        expect($expired->fresh()->status)->toBeFalse();
        expect($active->fresh()->status)->toBeTrue();
        expect($noExpire->fresh()->status)->toBeTrue();
    });

    it('is scheduled daily', function () {
        Bus::fake();

        $events = app(Schedule::class)->events();
        foreach ($events as $event) {
            $event->run(app());
        }

        Bus::assertDispatched(\App\Jobs\DisableExpiredQuizzesJob::class);
    });
});
