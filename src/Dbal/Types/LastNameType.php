<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\LastName;

class LastNameType extends AbstractStringType
{
    public const NAME = 'LastName';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return LastName::class;
    }
}
