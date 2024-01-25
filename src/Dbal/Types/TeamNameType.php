<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\TeamName;

class TeamNameType extends AbstractStringType
{
    public const NAME = 'TeamName';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return TeamName::class;
    }
}
