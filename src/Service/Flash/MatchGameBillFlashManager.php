<?php

declare(strict_types=1);

namespace App\Service\Flash;

use App\Entity\MatchGameBill;
use App\Message\Flash\MatchGameBill\MatchGameBillCreatedFlashMessage;
use App\Message\Flash\MatchGameBill\MatchGameBillDeletedFlashMessage;
use App\Message\Flash\MatchGameBill\MatchGameBillUpdatedFlashMessage;
use App\Service\FlashManager;

class MatchGameBillFlashManager extends FlashManager
{
    public function sendCreated(MatchGameBill $matchGameBill): void
    {
        $this->flash(new MatchGameBillCreatedFlashMessage($matchGameBill->getId()));
    }

    public function sendUpdated(MatchGameBill $matchGameBill): void
    {
        $this->flash(new MatchGameBillUpdatedFlashMessage($matchGameBill->getId()));
    }

    public function sendDeleted(MatchGameBill $matchGameBill): void
    {
        $this->flash(new MatchGameBillDeletedFlashMessage($matchGameBill->getId()));
    }
}
