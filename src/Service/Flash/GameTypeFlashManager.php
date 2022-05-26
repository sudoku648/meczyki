<?php

declare(strict_types=1);

namespace App\Service\Flash;

use App\Entity\GameType;
use App\Message\Flash\GameType\GameTypeCreatedFlashMessage;
use App\Message\Flash\GameType\GameTypeDeletedFlashMessage;
use App\Message\Flash\GameType\GameTypeUpdatedFlashMessage;
use App\Service\FlashManager;

class GameTypeFlashManager extends FlashManager
{
    public function sendCreated(GameType $gameType): void
    {
        $this->flash(new GameTypeCreatedFlashMessage($gameType->getId()));
    }

    public function sendUpdated(GameType $gameType): void
    {
        $this->flash(new GameTypeUpdatedFlashMessage($gameType->getId()));
    }

    public function sendDeleted(GameType $gameType): void
    {
        $this->flash(new GameTypeDeletedFlashMessage($gameType->getId()));
    }
}
