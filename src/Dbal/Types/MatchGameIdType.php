<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\MatchGameId;

class MatchGameIdType extends AbstractIdType
{
    public const NAME = 'MatchGameId';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return MatchGameId::class;
    }
}
