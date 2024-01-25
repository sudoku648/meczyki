<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

use function sprintf;

class MoneyException extends Exception
{
    public static function invalidCurrencies(string $currency1, string $currency2): self
    {
        return new self(sprintf('Money of currencies "%s" and "%s" cannot be compared.', $currency1, $currency2));
    }

    public static function currencyDoesNotExist(string $currency): self
    {
        return new self(sprintf('Currency "%s" doesn\'t exist.', $currency));
    }
}
