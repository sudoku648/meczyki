<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Domain\ValueObject;

class PercentValueObject extends FloatValueObject
{
    public static function byValue(float $value): static
    {
        return new static($value);
    }
}
