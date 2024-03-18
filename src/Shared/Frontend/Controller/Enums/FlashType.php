<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Frontend\Controller\Enums;

use function array_map;

enum FlashType: string
{
    case SUCCESS = 'success';
    case ERROR   = 'error';
    case WARNING = 'warning';
    case INFO    = 'info';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
}
