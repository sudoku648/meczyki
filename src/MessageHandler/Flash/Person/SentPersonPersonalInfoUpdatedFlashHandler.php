<?php

declare(strict_types=1);

namespace App\MessageHandler\Flash\Person;

use App\Message\Flash\Person\PersonPersonalInfoUpdatedFlashMessage;
use App\Repository\PersonRepository;
use App\Service\Flash\PersonFlashManager;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SentPersonPersonalInfoUpdatedFlashHandler implements MessageHandlerInterface
{
    private PersonFlashManager $manager;
    private PersonRepository $repository;

    public function __construct(
        PersonFlashManager $manager,
        PersonRepository $repository
    )
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    public function __invoke(PersonPersonalInfoUpdatedFlashMessage $message)
    {
        $person = $this->repository->find($message->getPersonId());

        if (!$person) {
            throw new UnrecoverableMessageHandlingException('Person not found.');
        }

        $this->manager->sendPersonalInfoUpdated($person);
    }
}
