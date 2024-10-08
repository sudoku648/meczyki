<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Infrastructure\Persistence\Dbal\Types;

use Override;
use Sudoku648\Meczyki\Club\Domain\ValueObject\ClubId;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types\AbstractIdType;

class ClubIdType extends AbstractIdType
{
    public const string NAME = 'ClubId';

    public function getName(): string
    {
        return static::NAME;
    }

    #[Override]
    protected function getValueObjectClassName(): string
    {
        return ClubId::class;
    }
}
