<?php

declare(strict_types=1);

namespace App\Message\Contracts;

interface UserRoleFlashMessageInterface extends FlashMessageInterface
{
    public function getUserRoleId(): int;
}
