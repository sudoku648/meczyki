<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\StringValueObject;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

use function is_a;
use function is_scalar;

abstract class AbstractStringType extends Type
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        $fqcn = $this->getValueObjectClassName();

        if (null === $value) {
            return null;
        }

        if (is_scalar($value)) {
            $value = $fqcn::fromString($value);
        }

        if (!is_a($value, $fqcn)) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $fqcn);
        }

        return (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?StringValueObject
    {
        $fqcn = $this->getValueObjectClassName();

        return null !== $value ? $fqcn::fromString($value) : null;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($fieldDeclaration);
    }

    abstract protected function getValueObjectClassName(): string;
}
