<?php

declare(strict_types=1);

namespace App\Enums;

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
            self::LOWER_SILESIAN      => 'dolnośląskie',
            self::KUYAVIAN_POMERANIAN => 'kujawsko-pomorskie',
            self::LUBLIN              => 'lubelskie',
            self::LUBUSZ              => 'lubuskie',
            self::LODZ                => 'łódzkie',
            self::LESSER_POLAND       => 'małopolskie',
            self::MASOVIAN            => 'mazowieckie',
            self::OPOLE               => 'opolskie',
            self::SUBCARPATHIAN       => 'podkarpackie',
            self::PODLASKIE           => 'podlaskie',
            self::POMERANIAN          => 'pomorskie',
            self::SILESIAN            => 'śląskie',
            self::HOLY_CROSS          => 'świętokrzyskie',
            self::WARMIAN_MASURIAN    => 'warmińsko-mazurskie',
            self::GREATER_POLAND      => 'wielkopolskie',
            self::WEST_POMERANIAN     => 'zachodniopomorskie',
            default                   => throw new LogicException(),
        };
    }
}
