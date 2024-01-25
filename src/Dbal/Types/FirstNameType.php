<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\FirstName;

class FirstNameType extends AbstractStringType
{
    public const NAME = 'FirstName';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return FirstName::class;
    }
}
