<?php

namespace App\DTOs;

use Spatie\DataTransferObject\DataTransferObject;

class SlackStatus extends DataTransferObject
{
    public string $emoji;
    public string $text;

    public static function empty(): self
    {
        return new static(emoji: '', text: '');
    }

    public static function meeting(): self
    {
        return new static(emoji: ':spiral_calendar_pad:', text: 'In a meeting');
    }

    public function equals(self $other): bool
    {
        return $this->emoji === $other->emoji
            && $this->text === $other->text;
    }
}
