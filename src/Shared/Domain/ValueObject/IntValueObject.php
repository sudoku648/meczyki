<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Domain\ValueObject;

abstract class IntValueObject implements ValueObjectInterface
{
    final protected function __construct(protected int $value)
    {
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public static function byValue(int $value): static
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
