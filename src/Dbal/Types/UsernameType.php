<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\Username;

class UsernameType extends AbstractStringType
{
    public const NAME = 'Username';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return Username::class;
    }
}
