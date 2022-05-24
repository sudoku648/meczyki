<?php

declare(strict_types=1);

namespace App\Message\Contracts;

interface UserFlashMessageInterface extends FlashMessageInterface
{
    public function getUserId(): int;
}
