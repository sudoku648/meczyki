<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\GameTypeId;

class GameTypeIdType extends AbstractIdType
{
    public const NAME = 'GameTypeId';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return GameTypeId::class;
    }
}
