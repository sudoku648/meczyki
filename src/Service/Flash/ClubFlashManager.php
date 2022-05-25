<?php

declare(strict_types=1);

namespace App\Service\Flash;

use App\Entity\Club;
use App\Message\Flash\Club\ClubCreatedFlashMessage;
use App\Message\Flash\Club\ClubDeletedFlashMessage;
use App\Message\Flash\Club\ClubUpdatedFlashMessage;
use App\Service\FlashManager;

class ClubFlashManager extends FlashManager
{
    public function sendCreated(Club $club): void
    {
        $this->flash(new ClubCreatedFlashMessage($club->getId()));
    }

    public function sendUpdated(Club $club): void
    {
        $this->flash(new ClubUpdatedFlashMessage($club->getId()));
    }

    public function sendDeleted(Club $club): void
    {
        $this->flash(new ClubDeletedFlashMessage($club->getId()));
    }
}
