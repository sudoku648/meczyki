<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\Nip;

class NipType extends AbstractStringType
{
    public const NAME = 'Nip';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return Nip::class;
    }
}
