<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Domain\ValueObject;

use Stringable;

abstract class StringValueObject implements ValueObjectInterface, Stringable
{
    final protected function __construct(protected string $value)
    {
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public static function fromString(string $value): static
    {
        return new static($value);
    }

    public function equals(ValueObjectInterface $object): bool
    {
        if (!$object instanceof static) {
            return false;
        }

        return $this->value === $object->getValue();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
