<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\TeamId;

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
