<?php

declare(strict_types=1);

namespace App\MessageHandler\Flash\Team;

use App\Message\Flash\Team\TeamUpdatedFlashMessage;
use App\Repository\TeamRepository;
use App\Service\Flash\TeamFlashManager;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SentTeamUpdatedFlashHandler implements MessageHandlerInterface
{
    private TeamFlashManager $manager;
    private TeamRepository $repository;

    public function __construct(
        TeamFlashManager $manager,
        TeamRepository $repository
    )
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    public function __invoke(TeamUpdatedFlashMessage $message)
    {
        $team = $this->repository->find($message->getTeamId());

        if (!$team) {
            throw new UnrecoverableMessageHandlingException('Team not found.');
        }

        $this->manager->sendUpdated($team);
    }
}