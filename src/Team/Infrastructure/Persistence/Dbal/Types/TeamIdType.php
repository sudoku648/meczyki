<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Infrastructure\Persistence\Dbal\Types;

use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types\AbstractIdType;
use Sudoku648\Meczyki\Team\Domain\ValueObject\TeamId;

class TeamIdType extends AbstractIdType
{
    public const NAME = 'TeamId';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return TeamId::class;
    }
}
