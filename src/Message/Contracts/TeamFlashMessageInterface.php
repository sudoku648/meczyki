<?php

declare(strict_types=1);

namespace App\Message\Contracts;

interface TeamFlashMessageInterface extends FlashMessageInterface
{
    public function getTeamId(): int;
}
