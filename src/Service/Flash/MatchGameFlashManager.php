<?php

declare(strict_types=1);

namespace App\Service\Flash;

use App\Entity\MatchGame;
use App\Message\Flash\MatchGame\MatchGameCreatedFlashMessage;
use App\Message\Flash\MatchGame\MatchGameDeletedFlashMessage;
use App\Message\Flash\MatchGame\MatchGameUpdatedFlashMessage;
use App\Service\FlashManager;

class MatchGameFlashManager extends FlashManager
{
    public function sendCreated(MatchGame $matchGame): void
    {
        $this->flash(new MatchGameCreatedFlashMessage($matchGame->getId()));
    }

    public function sendUpdated(MatchGame $matchGame): void
    {
        $this->flash(new MatchGameUpdatedFlashMessage($matchGame->getId()));
    }

    public function sendDeleted(MatchGame $matchGame): void
    {
        $this->flash(new MatchGameDeletedFlashMessage($matchGame->getId()));
    }
}
