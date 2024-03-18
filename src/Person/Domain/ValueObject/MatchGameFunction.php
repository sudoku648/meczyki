<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Domain\ValueObject;

use LogicException;

enum MatchGameFunction: string
{
    case DELEGATE         = 'delegate';
    case REFEREE          = 'referee';
    case REFEREE_OBSERVER = 'referee_observer';

    public function getName(): string
    {
        return match ($this) {
            self::DELEGATE         => 'delegate',
            self::REFEREE          => 'referee',
            self::REFEREE_OBSERVER => 'referee observer',
            default                => throw new LogicException(),
        };
    }
}
