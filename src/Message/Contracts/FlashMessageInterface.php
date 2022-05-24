<?php

declare(strict_types=1);

namespace App\Message\Contracts;

interface FlashMessageInterface
{
    public function getMessage(): string;
}
