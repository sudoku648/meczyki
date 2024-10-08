<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Infrastructure\Persistence\Dbal\Types;

use Override;
use Sudoku648\Meczyki\Club\Domain\ValueObject\ClubName;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types\AbstractStringType;

class ClubNameType extends AbstractStringType
{
    public const string NAME = 'ClubName';

    public function getName(): string
    {
        return static::NAME;
    }

    #[Override]
    protected function getValueObjectClassName(): string
    {
        return ClubName::class;
    }
}
