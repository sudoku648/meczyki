<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Domain\ValueObject;

abstract class FloatValueObject implements ValueObjectInterface
{
    final protected function __construct(protected readonly float $value)
    {
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public static function byValue(float $value): static
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
