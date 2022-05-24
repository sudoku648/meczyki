<?php

declare(strict_types=1);

namespace App\Service\Flash;

use App\Entity\Person;
use App\Message\Flash\Person\PersonCreatedFlashMessage;
use App\Message\Flash\Person\PersonDeletedFlashMessage;
use App\Message\Flash\Person\PersonUpdatedFlashMessage;
use App\Service\FlashManager;

class PersonFlashManager extends FlashManager
{
    public function sendCreated(Person $person): void
    {
        $this->flash(new PersonCreatedFlashMessage($person->getId()));
    }

    public function sendUpdated(Person $person): void
    {
        $this->flash(new PersonUpdatedFlashMessage($person->getId()));
    }

    public function sendDeleted(Person $person): void
    {
        $this->flash(new PersonDeletedFlashMessage($person->getId()));
    }
}
