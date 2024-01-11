<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\UserRoleId;

class UserRoleIdType extends AbstractIdType
{
    public const NAME = 'UserRoleId';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return UserRoleId::class;
    }
}
