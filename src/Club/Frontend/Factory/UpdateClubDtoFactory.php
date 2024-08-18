<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Frontend\Factory;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Club\Frontend\Dto\UpdateClubDto;

class UpdateClubDtoFactory
{
    public static function fromEntity(Club $club): UpdateClubDto
    {
        return new UpdateClubDto(
            $club->getId(),
            $club->getName()->getValue(),
            $club->getEmblem(),
        );
    }
}
