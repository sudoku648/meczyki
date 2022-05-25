<?php

declare(strict_types=1);

namespace App\Service\Flash;

use App\Entity\Team;
use App\Message\Flash\Team\TeamCreatedFlashMessage;
use App\Message\Flash\Team\TeamDeletedFlashMessage;
use App\Message\Flash\Team\TeamUpdatedFlashMessage;
use App\Service\FlashManager;

class TeamFlashManager extends FlashManager
{
    public function sendCreated(Team $team): void
    {
        $this->flash(new TeamCreatedFlashMessage($team->getId()));
    }

    public function sendUpdated(Team $team): void
    {
        $this->flash(new TeamUpdatedFlashMessage($team->getId()));
    }

    public function sendDeleted(Team $team): void
    {
        $this->flash(new TeamDeletedFlashMessage($team->getId()));
    }
}
