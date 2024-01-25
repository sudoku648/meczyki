<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\GameTypeName;

class GameTypeNameType extends AbstractStringType
{
    public const NAME = 'GameTypeName';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return GameTypeName::class;
    }
}
