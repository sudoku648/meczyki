<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\ClubDto;
use App\Entity\Club;
use App\ValueObject\ClubName;

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
