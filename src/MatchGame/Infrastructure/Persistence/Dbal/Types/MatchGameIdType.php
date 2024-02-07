<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Infrastructure\Persistence\Dbal\Types;

use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\MatchGameId;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types\AbstractIdType;

class MatchGameIdType extends AbstractIdType
{
    public const NAME = 'MatchGameId';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return MatchGameId::class;
    }
}
