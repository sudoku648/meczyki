<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Domain\ValueObject;

use LogicException;

enum Voivodeship: string
{
    case LOWER_SILESIAN      = 'lower_silesian';
    case KUYAVIAN_POMERANIAN = 'kuyavian-pomeranian';
    case LUBLIN              = 'lublin';
    case LUBUSZ              = 'lubusz';
    case LODZ                = 'lodz';
    case LESSER_POLAND       = 'lesser_poland';
    case MASOVIAN            = 'masovian';
    case OPOLE               = 'opole';
    case SUBCARPATHIAN       = 'subcarpathian';
    case PODLASKIE           = 'podlaskie';
    case POMERANIAN          = 'pomeranian';
    case SILESIAN            = 'silesian';
    case HOLY_CROSS          = 'holy_cross';
    case WARMIAN_MASURIAN    = 'warmian-masurian';
    case GREATER_POLAND      = 'greater_poland';
    case WEST_POMERANIAN     = 'west_pomeranian';

    public function getName(): string
    {
        return match ($this) {
            self::LOWER_SILESIAN      => 'lower silesian',
            self::KUYAVIAN_POMERANIAN => 'kuyavian-pomeranian',
            self::LUBLIN              => 'lublin',
            self::LUBUSZ              => 'lubusz',
            self::LODZ                => 'lodz',
            self::LESSER_POLAND       => 'lesser poland',
            self::MASOVIAN            => 'masovian',
            self::OPOLE               => 'opole',
            self::SUBCARPATHIAN       => 'subcarpathian',
            self::PODLASKIE           => 'podlaskie',
            self::POMERANIAN          => 'pomeranian',
            self::SILESIAN            => 'silesian',
            self::HOLY_CROSS          => 'holy cross',
            self::WARMIAN_MASURIAN    => 'warmian-masurian',
            self::GREATER_POLAND      => 'greater poland',
            self::WEST_POMERANIAN     => 'west pomeranian',
            default                   => throw new LogicException(),
        };
    }
}
