<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\ClubId;

class ClubIdType extends AbstractIdType
{
    public const NAME = 'ClubId';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return ClubId::class;
    }
}
