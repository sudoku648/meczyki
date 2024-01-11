<?php

declare(strict_types=1);

namespace App\ValueObject\Contract;

interface ValueObjectInterface
{
    public function equals(self $object): bool;

    public function getValue(): mixed;
}
