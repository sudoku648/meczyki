<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Infrastructure\Persistence\Dbal\Types;

use Sudoku648\Meczyki\GameType\Domain\ValueObject\GameTypeName;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types\AbstractStringType;

class GameTypeNameType extends AbstractStringType
{
    public const NAME = 'GameTypeName';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return GameTypeName::class;
    }
}
