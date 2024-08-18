<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Frontend\Factory;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Club\Frontend\Dto\ClubDto;

class ClubDtoFactory
{
    public static function fromEntity(Club $club): ClubDto
    {
        return new ClubDto(
            $club->getId(),
            $club->getName()->getValue(),
            $club->getEmblem(),
        );
    }
}
