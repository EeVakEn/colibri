<?php

namespace App\Enum;

enum ContentTypes: string
{
    case Article = 'article';
    case Video = 'video';

    public static function toMap(): array
    {
        return array_combine(
            array_column(self::cases(), 'value'),
            array_map(fn($case) => ucfirst($case->name), self::cases())
        );
    }
}
