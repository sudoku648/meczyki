<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Frontend\Factory;

use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;
use Sudoku648\Meczyki\GameType\Frontend\Dto\GameTypeDto;

class GameTypeDtoFactory
{
    public static function fromEntity(GameType $gameType): GameTypeDto
    {
        return new GameTypeDto(
            $gameType->getId(),
            $gameType->getName()->getValue(),
            $gameType->isOfficial(),
            $gameType->getImage(),
        );
    }
}
