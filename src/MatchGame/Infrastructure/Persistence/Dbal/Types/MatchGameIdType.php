<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Infrastructure\Persistence\Dbal\Types;

use Override;
use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\MatchGameId;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types\AbstractIdType;

class MatchGameIdType extends AbstractIdType
{
    public const string NAME = 'MatchGameId';

    public function getName(): string
    {
        return static::NAME;
    }

    #[Override]
    protected function getValueObjectClassName(): string
    {
        return MatchGameId::class;
    }
}
