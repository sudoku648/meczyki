<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\UserId;

class UserIdType extends AbstractIdType
{
    public const NAME = 'UserId';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return UserId::class;
    }
}
