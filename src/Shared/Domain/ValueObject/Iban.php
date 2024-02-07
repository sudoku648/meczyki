<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Domain\ValueObject;

use Sudoku648\Meczyki\Shared\Domain\Exception\IbanException;
use Symfony\Component\Validator\Constraints\Iban as IbanConstraint;
use Symfony\Component\Validator\Validation;

use function count;
use function substr;

/**
 * @todo prefix and number should not be nullable - waiting for Doctrine 3.0 for nullable embeddables
 */
final class Iban
{
    final public function __construct(
        private readonly ?string $prefix,
        private readonly ?string $number,
    ) {
    }

    public static function fromString(string $value): static
    {
        $validator  = Validation::createValidator();
        $violations = $validator->validate($value, [new IbanConstraint()]);

        if (0 !== count($violations)) {
            throw IbanException::ibanIsInvalid($value);
        }

        return new self(substr($value, 0, 2), substr($value, 2));
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function equals(self $other): bool
    {
        return $this->prefix === $other->prefix
            && $this->number === $other->number;
    }
}
