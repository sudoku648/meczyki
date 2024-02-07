<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Infrastructure\Persistence\Dbal\Types;

use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types\AbstractStringType;
use Sudoku648\Meczyki\Team\Domain\ValueObject\TeamShortName;

class TeamShortNameType extends AbstractStringType
{
    public const NAME = 'TeamShortName';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return TeamShortName::class;
    }
}
