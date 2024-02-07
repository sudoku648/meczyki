<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Domain\Exception;

use Exception;

use function sprintf;

class InvalidIdException extends Exception
{
    public static function create(string $value): self
    {
        return new self(sprintf('"%s" is not a valid id.', $value));
    }
}
