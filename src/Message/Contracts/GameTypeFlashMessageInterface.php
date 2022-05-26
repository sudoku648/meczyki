<?php

declare(strict_types=1);

namespace App\Message\Contracts;

interface GameTypeFlashMessageInterface extends FlashMessageInterface
{
    public function getGameTypeId(): int;
}
