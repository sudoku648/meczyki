<?php

declare(strict_types=1);

namespace App\ValueObject;

class PercentValueObject extends FloatValueObject
{
    public static function byValue(float $value): static
    {
        return new static($value);
    }
}
