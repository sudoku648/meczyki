<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\TeamShortName;

class TeamShortNameType extends AbstractStringType
{
    public const NAME = 'TeamShortName';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return TeamShortName::class;
    }
}
