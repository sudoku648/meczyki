<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Infrastructure\Persistence\Dbal\Types;

use Override;
use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\Venue;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types\AbstractStringType;

class VenueType extends AbstractStringType
{
    public const NAME = 'Venue';

    public function getName(): string
    {
        return static::NAME;
    }

    #[Override]
    protected function getValueObjectClassName(): string
    {
        return Venue::class;
    }
}
