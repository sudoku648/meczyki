<?php

declare(strict_types=1);

namespace App\MessageHandler\Flash\MatchGame;

use App\Message\Flash\MatchGame\MatchGameDeletedFlashMessage;
use App\Repository\MatchGameRepository;
use App\Service\Flash\MatchGameFlashManager;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SentMatchGameDeletedFlashHandler implements MessageHandlerInterface
{
    private MatchGameFlashManager $manager;
    private MatchGameRepository $repository;

    public function __construct(
        MatchGameFlashManager $manager,
        MatchGameRepository $repository
    )
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    public function __invoke(MatchGameDeletedFlashMessage $message)
    {
        $matchGame = $this->repository->find($message->getMatchGameId());

        if (!$matchGame) {
            throw new UnrecoverableMessageHandlingException('Match game not found.');
        }

        $this->manager->sendDeleted($matchGame);
    }
}
