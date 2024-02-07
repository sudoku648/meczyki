<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Frontend\Factory;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Club\Domain\ValueObject\ClubName;
use Sudoku648\Meczyki\Club\Frontend\Dto\ClubDto;

class ClubFactory
{
    public function createFromDto(ClubDto $dto): Club
    {
        return new Club(ClubName::fromString($dto->name));
    }

    public function createDto(Club $club): ClubDto
    {
        $dto = new ClubDto();

        $dto->name   = $club->getName()->getValue();
        $dto->emblem = $club->getEmblem();
        $dto->setId($club->getId());

        return $dto;
    }
}
