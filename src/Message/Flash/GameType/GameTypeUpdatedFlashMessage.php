<?php

declare(strict_types=1);

namespace App\Message\Flash\GameType;

use App\Message\Contracts\GameTypeFlashMessageInterface;

class GameTypeUpdatedFlashMessage implements GameTypeFlashMessageInterface
{
    private int $gameTypeId;

    public function __construct(int $gameTypeId)
    {
        $this->gameTypeId = $gameTypeId;
    }

    public function getGameTypeId(): int
    {
        return $this->gameTypeId;
    }

    public function getMessage(): string
    {
        return 'Typ rozgrywek został zaktualizowany.';
    }
}
