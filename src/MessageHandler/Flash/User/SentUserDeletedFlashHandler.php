<?php

declare(strict_types=1);

namespace App\MessageHandler\Flash\User;

use App\Message\Flash\User\UserDeletedFlashMessage;
use App\Repository\UserRepository;
use App\Service\Flash\UserFlashManager;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SentUserDeletedFlashHandler implements MessageHandlerInterface
{
    private UserFlashManager $manager;
    private UserRepository $repository;

    public function __construct(
        UserFlashManager $manager,
        UserRepository $repository
    )
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    public function __invoke(UserDeletedFlashMessage $message)
    {
        $user = $this->repository->find($message->getUserId());

        if (!$user) {
            throw new UnrecoverableMessageHandlingException('User not found.');
        }

        $this->manager->sendDeleted($user);
    }
}