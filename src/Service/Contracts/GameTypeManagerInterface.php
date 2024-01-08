<?php

declare(strict_types=1);

namespace App\Service\Contracts;

use App\Dto\GameTypeDto;
use App\Entity\GameType;

interface GameTypeManagerInterface
{
    public function create(GameTypeDto $dto): GameType;

    public function edit(GameType $gameType, GameTypeDto $dto): GameType;

    public function delete(GameType $gameType): void;

    public function detachImage(GameType $gameType): void;

    public function createDto(GameType $gameType): GameTypeDto;
}
