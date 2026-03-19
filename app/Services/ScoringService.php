<?php

namespace App\Services;

use App\Models\Question;
use App\OptionScoreEnum;

class ScoringService
{
    public static function calculate(Question $question, $response): float
    {
        if ($question->type === 'text')
            return 100;

        $options = $question->data['options'] ?? [];

        $responses = is_array($response) ? $response : [$response];

        $scores = collect($responses)->map(function ($selectedValue) use ($options) {
            $option = collect($options)->firstWhere('text', $selectedValue);
            // firstWhere('id', $selectedValue)
            if (!$option || empty($option['score']))
                return 0;

            return OptionScoreEnum::from($option['score'])->mark();
        });

        $sum = $scores->sum();
        $max = count($responses) * 100;

        return $max > 0 ? ($sum / $max) * 100 : 0;
    }
}
