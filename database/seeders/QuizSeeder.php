<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{

    public function run(): void
    {
        \App\Models\Quiz::factory()
            ->count(5)
            ->hasQuestions(10)
            ->create();
    }
}
