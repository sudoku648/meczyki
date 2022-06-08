<?php

declare(strict_types=1);

namespace App\Message\Flash\MatchGameBill;

use App\Message\Contracts\MatchGameBillFlashMessageInterface;

class MatchGameBillDeletedFlashMessage implements MatchGameBillFlashMessageInterface
{
    private int $matchGameBillId;

    public function __construct(int $matchGameBillId)
    {
        $this->matchGameBillId = $matchGameBillId;
    }

    public function getMatchGameBillId(): int
    {
        return $this->matchGameBillId;
    }

    public function getMessage(): string
    {
        return 'Rachunek meczowy został usunięty.';
    }
}
