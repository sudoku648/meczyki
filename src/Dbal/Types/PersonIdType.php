<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\PersonId;

class PersonIdType extends AbstractIdType
{
    public const NAME = 'PersonId';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return PersonId::class;
    }
}
