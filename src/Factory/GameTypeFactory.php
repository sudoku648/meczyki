<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\GameTypeDto;
use App\Entity\GameType;
use App\ValueObject\GameTypeName;

class GameTypeFactory
{
    public function createFromDto(GameTypeDto $dto): GameType
    {
        return new GameType(
            GameTypeName::fromString($dto->name),
            true === $dto->isOfficial,
        );
    }

    public function createDto(GameType $gameType): GameTypeDto
    {
        $dto = new GameTypeDto();

        $dto->name       = $gameType->getName()->getValue();
        $dto->isOfficial = $gameType->isOfficial();
        $dto->image      = $gameType->getImage();
        $dto->setId($gameType->getId());

        return $dto;
    }
}
