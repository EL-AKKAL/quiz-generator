<?php

namespace App;

enum OptionScoreEnum: string
{
    case VERY_BAD = 'very bad';
    case BAD = 'bad';
    case AVERAGE = 'average';
    case GOOD = 'good';
    case EXCELLENT = 'excellent';

    public function label(): string
    {
        return match ($this) {
            self::VERY_BAD => 'Very bad',
            self::BAD => 'Bad',
            self::AVERAGE => 'Average',
            self::GOOD => 'Good',
            self::EXCELLENT => 'Excellent',
        };
    }

    public function mark(): string
    {
        return match ($this) {
            self::VERY_BAD => 0,
            self::BAD => 25,
            self::AVERAGE => 50,
            self::GOOD => 75,
            self::EXCELLENT => 100,
        };
    }

    public static function options(): array
    {
        return array_map(fn ($case) => [
            'value' => $case->value,
            'label' => $case->label(),
            'mark' => $case->mark(),
        ], self::cases());
    }
}
