<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Frontend\Factory;

use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;
use Sudoku648\Meczyki\GameType\Domain\ValueObject\GameTypeName;
use Sudoku648\Meczyki\GameType\Frontend\Dto\GameTypeDto;

class GameTypeFactory
{
    public function createFromDto(GameTypeDto $dto): GameType
    {
        return new GameType(
            GameTypeName::fromString($dto->name),
            $dto->isOfficial,
        );
    }

    public function createDto(GameType $gameType): GameTypeDto
    {
        $dto = new GameTypeDto();

        $dto->setId($gameType->getId());
        $dto->name       = $gameType->getName()->getValue();
        $dto->isOfficial = $gameType->isOfficial();
        $dto->image      = $gameType->getImage();

        return $dto;
    }
}
