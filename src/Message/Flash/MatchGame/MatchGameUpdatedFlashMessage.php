<?php

declare(strict_types=1);

namespace App\Message\Flash\MatchGame;

use App\Message\Contracts\MatchGameFlashMessageInterface;

class MatchGameUpdatedFlashMessage implements MatchGameFlashMessageInterface
{
    private int $matchGameId;

    public function __construct(int $matchGameId)
    {
        $this->matchGameId = $matchGameId;
    }

    public function getMatchGameId(): int
    {
        return $this->matchGameId;
    }

    public function getMessage(): string
    {
        return 'Mecz został zaktualizowany.';
    }
}
