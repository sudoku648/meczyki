<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Override;
use Sudoku648\Meczyki\Shared\Domain\ValueObject\PhoneNumber;

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

    #[Override]
    protected function getValueObjectClassName(): string
    {
        return PhoneNumber::class;
    }
}
