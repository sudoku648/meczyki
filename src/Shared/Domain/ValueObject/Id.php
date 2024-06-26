<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Domain\ValueObject;

use Stringable;
use Sudoku648\Meczyki\Shared\Domain\Exception\InvalidIdException;
use Symfony\Component\Uid\Uuid;

class Id implements Stringable, ValueObjectInterface
{
    final protected function __construct(protected readonly string $value = '')
    {
    }

    final public static function generate(string $value = null): static
    {
        return new static(Uuid::v7()->toRfc4122());
    }

    final public static function fromString(string $value): static
    {
        if (!Uuid::isValid($value)) {
            throw InvalidIdException::create($value);
        }

        return new static($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(ValueObjectInterface $object): bool
    {
        if (!$object instanceof static) {
            return false;
        }

        return $this->value === $object->getValue();
    }
}
