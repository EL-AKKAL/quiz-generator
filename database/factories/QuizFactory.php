<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class QuizFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::inRandomOrder()->value('id'),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(3),
            'status' => $this->faker->boolean(),
            'expire_date' => $this->faker->dateTimeBetween('now', '+1 year'),
        ];
    }
}
