<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\Season;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class SeasonType extends AbstractStringType
{
    public const NAME = 'Season';

    public function getName(): string
    {
        return static::NAME;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        $fieldDeclaration['length'] = 9;
        $fieldDeclaration['fixed']  = true;

        return $platform->getStringTypeDeclarationSQL($fieldDeclaration);
    }

    protected function getValueObjectClassName(): string
    {
        return Season::class;
    }
}
