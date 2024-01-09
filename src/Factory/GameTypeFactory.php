<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\GameTypeDto;
use App\Entity\GameType;

class GameTypeFactory
{
    public function createFromDto(GameTypeDto $dto): GameType
    {
        return new GameType(
            $dto->name,
            $dto->group,
            $dto->isOfficial
        );
    }

    public function createDto(GameType $gameType): GameTypeDto
    {
        $dto = new GameTypeDto();

        $dto->name         = $gameType->getName();
        $dto->group        = $gameType->getGroup();
        $dto->fullName     = $gameType->getFullName();
        $dto->isOfficial   = $gameType->isOfficial();
        $dto->image        = $gameType->getImage();
        $dto->createdAt    = $gameType->getCreatedAt();
        $dto->createdAtAgo = $gameType->getCreatedAtAgo();
        $dto->updatedAt    = $gameType->getUpdatedAt();
        $dto->updatedAtAgo = $gameType->getUpdatedAtAgo();
        $dto->setId($gameType->getId());

        return $dto;
    }
}
