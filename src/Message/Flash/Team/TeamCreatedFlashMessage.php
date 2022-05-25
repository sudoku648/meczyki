<?php

declare(strict_types=1);

namespace App\Message\Flash\Team;

use App\Message\Contracts\TeamFlashMessageInterface;

class TeamCreatedFlashMessage implements TeamFlashMessageInterface
{
    private int $teamId;

    public function __construct(int $teamId)
    {
        $this->teamId = $teamId;
    }

    public function getTeamId(): int
    {
        return $this->teamId;
    }

    public function getMessage(): string
    {
        return 'Drużyna została dodana.';
    }
}
