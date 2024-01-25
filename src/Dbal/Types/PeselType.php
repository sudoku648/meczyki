<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\Pesel;

class PeselType extends AbstractStringType
{
    public const NAME = 'Pesel';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return Pesel::class;
    }
}
