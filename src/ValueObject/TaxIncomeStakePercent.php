<?php

declare(strict_types=1);

namespace App\ValueObject;

use InvalidArgumentException;

use function sprintf;

final class TaxIncomeStakePercent extends PercentValueObject
{
    public static function byValue(float $value): static
    {
        if ($value < 0.0 || $value > 100.0) {
            throw new InvalidArgumentException(sprintf('Percent value must be between 0 and 100. %s given.', $value));
        }

        return new static($value);
    }
}
