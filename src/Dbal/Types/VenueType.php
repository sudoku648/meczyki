<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\Venue;

class VenueType extends AbstractStringType
{
    public const NAME = 'Venue';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return Venue::class;
    }
}
