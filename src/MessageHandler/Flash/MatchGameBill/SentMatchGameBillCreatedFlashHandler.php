<?php

declare(strict_types=1);

namespace App\MessageHandler\Flash\MatchGameBill;

use App\Message\Flash\MatchGameBill\MatchGameBillCreatedFlashMessage;
use App\Repository\MatchGameBillRepository;
use App\Service\Flash\MatchGameBillFlashManager;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SentMatchGameBillCreatedFlashHandler implements MessageHandlerInterface
{
    private MatchGameBillFlashManager $manager;
    private MatchGameBillRepository $repository;

    public function __construct(
        MatchGameBillFlashManager $manager,
        MatchGameBillRepository $repository
    )
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    public function __invoke(MatchGameBillCreatedFlashMessage $message)
    {
        $matchGameBill = $this->repository->find($message->getMatchGameBillId());

        if (!$matchGameBill) {
            throw new UnrecoverableMessageHandlingException('Match game bill not found.');
        }

        $this->manager->sendCreated($matchGameBill);
    }
}
