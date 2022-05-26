<?php

declare(strict_types=1);

namespace App\MessageHandler\Flash\GameType;

use App\Message\Flash\GameType\GameTypeUpdatedFlashMessage;
use App\Repository\GameTypeRepository;
use App\Service\Flash\GameTypeFlashManager;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SentGameTypeUpdatedFlashHandler implements MessageHandlerInterface
{
    private GameTypeFlashManager $manager;
    private GameTypeRepository $repository;

    public function __construct(
        GameTypeFlashManager $manager,
        GameTypeRepository $repository
    )
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    public function __invoke(GameTypeUpdatedFlashMessage $message)
    {
        $gameType = $this->repository->find($message->getGameTypeId());

        if (!$gameType) {
            throw new UnrecoverableMessageHandlingException('Game type not found.');
        }

        $this->manager->sendUpdated($gameType);
    }
}
