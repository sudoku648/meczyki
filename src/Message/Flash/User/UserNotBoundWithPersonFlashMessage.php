<?php

declare(strict_types=1);

namespace App\Message\Flash\User;

use App\Message\Contracts\UserFlashMessageInterface;

class UserNotBoundWithPersonFlashMessage implements UserFlashMessageInterface
{
    private int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getMessage(): string
    {
        return 'Osoba jest już przypięta do użytkownika.';
    }
}