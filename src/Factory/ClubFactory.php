<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\ClubDto;
use App\Entity\Club;
use App\Factory\Contracts\ContentFactoryInterface;

class ClubFactory implements ContentFactoryInterface
{
    public function createFromDto(ClubDto $dto): Club
    {
        return new Club(
            $dto->name
        );
    }

    public function createDto(Club $club): ClubDto
    {
        $dto = new ClubDto();

        $dto->name         = $club->getName();
        $dto->emblem       = $club->getEmblem();
        $dto->createdAt    = $club->getCreatedAt();
        $dto->createdAtAgo = $club->getCreatedAtAgo();
        $dto->updatedAt    = $club->getUpdatedAt();
        $dto->updatedAtAgo = $club->getUpdatedAtAgo();
        $dto->setId($club->getId());

        return $dto;
    }
}
