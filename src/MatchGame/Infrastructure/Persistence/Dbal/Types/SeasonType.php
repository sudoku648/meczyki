<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Infrastructure\Persistence\Dbal\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\Season;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types\AbstractStringType;

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
