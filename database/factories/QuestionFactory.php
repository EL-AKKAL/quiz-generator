<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{

    public function definition(): array
    {
        return [
            'type' => $this->faker->word(),
            'question' => $this->faker->sentence(),
            'description' => $this->faker->sentence(),
            'data' => $this->faker->text(),
            'quiz_id' => \App\Models\Quiz::factory(),
        ];
    }
}
