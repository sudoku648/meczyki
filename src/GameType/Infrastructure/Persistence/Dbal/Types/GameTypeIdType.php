<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Infrastructure\Persistence\Dbal\Types;

use Sudoku648\Meczyki\GameType\Domain\ValueObject\GameTypeId;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types\AbstractIdType;

class GameTypeIdType extends AbstractIdType
{
    public const NAME = 'GameTypeId';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return GameTypeId::class;
    }
}
