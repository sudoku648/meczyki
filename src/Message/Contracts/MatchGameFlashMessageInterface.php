<?php

declare(strict_types=1);

namespace App\Message\Contracts;

interface MatchGameFlashMessageInterface extends FlashMessageInterface
{
    public function getMatchGameId(): int;
}
