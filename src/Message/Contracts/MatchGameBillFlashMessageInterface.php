<?php

declare(strict_types=1);

namespace App\Message\Contracts;

interface MatchGameBillFlashMessageInterface extends FlashMessageInterface
{
    public function getMatchGameBillId(): int;
}
