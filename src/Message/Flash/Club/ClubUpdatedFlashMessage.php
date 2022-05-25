<?php

declare(strict_types=1);

namespace App\Message\Flash\Club;

use App\Message\Contracts\ClubFlashMessageInterface;

class ClubUpdatedFlashMessage implements ClubFlashMessageInterface
{
    private int $clubId;

    public function __construct(int $clubId)
    {
        $this->clubId = $clubId;
    }

    public function getClubId(): int
    {
        return $this->clubId;
    }

    public function getMessage(): string
    {
        return 'Klub został zaktualizowany.';
    }
}
