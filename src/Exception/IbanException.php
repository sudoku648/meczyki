<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

use function sprintf;

class IbanException extends Exception
{
    public static function ibanIsInvalid(string $iban): self
    {
        return new self(sprintf('Iban "%s" is invalid.', $iban));
    }
}
