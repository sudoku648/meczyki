<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\ClubName;

class ClubNameType extends AbstractStringType
{
    public const NAME = 'ClubName';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return ClubName::class;
    }
}
