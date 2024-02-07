<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Sudoku648\Meczyki\Shared\Domain\ValueObject\Id;

use function is_a;
use function is_scalar;

abstract class AbstractIdType extends Type
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

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Id
    {
        $fqcn = $this->getValueObjectClassName();

        return null !== $value ? $fqcn::fromString($value) : null;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        $fieldDeclaration['length'] = 36;
        $fieldDeclaration['fixed']  = true;

        return $platform->getStringTypeDeclarationSQL($fieldDeclaration);
    }

    abstract protected function getValueObjectClassName(): string;
}
