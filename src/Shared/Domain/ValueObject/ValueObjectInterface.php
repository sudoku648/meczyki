<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Domain\ValueObject;

interface ValueObjectInterface
{
    public function equals(self $object): bool;

    public function getValue(): mixed;
}
