<?php

declare(strict_types=1);

namespace App\Message\Contracts;

interface ClubFlashMessageInterface extends FlashMessageInterface
{
    public function getClubId(): int;
}
