<?php

declare(strict_types=1);

namespace App\MessageHandler\Flash\Club;

use App\Message\Flash\Club\ClubDeletedFlashMessage;
use App\Repository\ClubRepository;
use App\Service\Flash\ClubFlashManager;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SentClubDeletedFlashHandler implements MessageHandlerInterface
{
    private ClubFlashManager $manager;
    private ClubRepository $repository;

    public function __construct(
        ClubFlashManager $manager,
        ClubRepository $repository
    )
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    public function __invoke(ClubDeletedFlashMessage $message)
    {
        $club = $this->repository->find($message->getClubId());

        if (!$club) {
            throw new UnrecoverableMessageHandlingException('Club not found.');
        }

        $this->manager->sendDeleted($club);
    }
}