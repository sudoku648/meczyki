<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\BoolValueObject;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\DBAL\Types\ConversionException;

use function is_a;
use function is_bool;

abstract class AbstractBoolType extends BooleanType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): bool|int|null
    {
        $fqcn = $this->getValueObjectClassName();

        if (null === $value) {
            return null;
        }

        if (is_bool($value)) {
            $value = $fqcn::byValue($value);
        }

        if (!is_a($value, $fqcn)) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $fqcn);
        }

        return $platform->convertBooleansToDatabaseValue($value->getValue());
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?BoolValueObject
    {
        $fqcn = $this->getValueObjectClassName();

        return null !== $value ? $fqcn::byValue((bool) $value) : null;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getBooleanTypeDeclarationSQL($fieldDeclaration);
    }

    abstract protected function getValueObjectClassName(): string;
}
