<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Sudoku648\Meczyki\Shared\Domain\ValueObject\IntValueObject;

use function is_a;
use function is_numeric;

abstract class AbstractIntType extends Type
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
    {
        $fqcn = $this->getValueObjectClassName();

        if (null === $value) {
            return null;
        }

        if (is_numeric($value)) {
            $value = $fqcn::byValue($value);
        }

        if (!is_a($value, $fqcn)) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $fqcn);
        }

        return (int) $value->getValue();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?IntValueObject
    {
        $fqcn = $this->getValueObjectClassName();

        return null !== $value ? $fqcn::byValue($value) : null;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getIntegerTypeDeclarationSQL($fieldDeclaration);
    }

    abstract protected function getValueObjectClassName(): string;
}
