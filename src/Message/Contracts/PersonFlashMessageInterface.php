<?php

declare(strict_types=1);

namespace App\Message\Contracts;

interface PersonFlashMessageInterface extends FlashMessageInterface
{
    public function getPersonId(): int;
}
