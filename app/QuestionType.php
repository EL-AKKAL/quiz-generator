<?php

namespace App;

enum QuestionType: string
{
    case SELECT = 'select';
    case RADIO = 'radio';
    case TEXT = 'text';
    case MULTIPLE = 'multiple';

    public static function random(): self
    {
        return collect(self::cases())->random();
    }

    public function isChoiceBased(): bool
    {
        return in_array($this, [self::SELECT, self::RADIO, self::MULTIPLE], true);
    }

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }

}
