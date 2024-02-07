<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Domain\ValueObject;

abstract class BoolValueObject implements ValueObjectInterface
{
    final protected function __construct(protected bool $value)
    {
    }

    public function getValue(): bool
    {
        return $this->value;
    }

    public static function byValue(bool $value): static
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
}
