<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\PhoneNumber;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class PhoneNumberType extends AbstractStringType
{
    public const NAME = 'PhoneNumber';

    public function getName(): string
    {
        return static::NAME;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        $fieldDeclaration['length'] = 12;
        $fieldDeclaration['fixed']  = true;

        return $platform->getStringTypeDeclarationSQL($fieldDeclaration);
    }

    protected function getValueObjectClassName(): string
    {
        return PhoneNumber::class;
    }
}
