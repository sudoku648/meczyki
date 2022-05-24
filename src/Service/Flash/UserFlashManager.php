<?php

declare(strict_types=1);

namespace App\Service\Flash;

use App\Entity\User;
use App\Message\Flash\User\UserActivatedFlashMessage;
use App\Message\Flash\User\UserCreatedFlashMessage;
use App\Message\Flash\User\UserDeactivatedFlashMessage;
use App\Message\Flash\User\UserDeletedFlashMessage;
use App\Message\Flash\User\UserUpdatedFlashMessage;
use App\Service\FlashManager;

class UserFlashManager extends FlashManager
{
    public function sendCreated(User $user): void
    {
        $this->flash(new UserCreatedFlashMessage($user->getId()));
    }

    public function sendUpdated(User $user): void
    {
        $this->flash(new UserUpdatedFlashMessage($user->getId()));
    }

    public function sendDeleted(User $user): void
    {
        $this->flash(new UserDeletedFlashMessage($user->getId()));
    }

    public function sendActivated(User $user): void
    {
        $this->flash(new UserActivatedFlashMessage($user->getId()));
    }

    public function sendDeactivated(User $user): void
    {
        $this->flash(new UserDeactivatedFlashMessage($user->getId()));
    }
}
