<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\TeamDto;
use App\Entity\Team;
use App\Factory\Contracts\ContentFactoryInterface;

class TeamFactory implements ContentFactoryInterface
{
    public function createFromDto(TeamDto $dto): Team
    {
        return new Team(
            $dto->fullName,
            $dto->shortName,
            $dto->club
        );
    }

    public function createDto(Team $team): TeamDto
    {
        $dto = new TeamDto();

        $dto->fullName  = $team->getFullName();
        $dto->shortName = $team->getShortName();
        $dto->club      = $team->getClub();
        $dto->createdAt = $team->getCreatedAt();
        $dto->updatedAt = $team->getUpdatedAt();
        $dto->setId($team->getId());

        return $dto;
    }
}
