<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\Round;

class RoundType extends AbstractIntType
{
    public const NAME = 'Round';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return Round::class;
    }
}
