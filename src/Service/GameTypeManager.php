<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\GameTypeDto;
use App\Entity\GameType;
use App\Event\GameType\GameTypeCreatedEvent;
use App\Event\GameType\GameTypeDeletedEvent;
use App\Event\GameType\GameTypeUpdatedEvent;
use App\Factory\GameTypeFactory;
use App\Message\DeleteImageMessage;
use App\Service\Contracts\GameTypeManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class GameTypeManager implements GameTypeManagerInterface
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $bus,
        private GameTypeFactory $factory,
    ) {
    }

    public function create(GameTypeDto $dto): GameType
    {
        $gameType = $this->factory->createFromDto($dto);

        $this->entityManager->persist($gameType);
        $this->entityManager->flush();

        $this->dispatcher->dispatch(new GameTypeCreatedEvent($gameType));

        return $gameType;
    }

    public function edit(GameType $gameType, GameTypeDto $dto): GameType
    {
        $gameType->setName($dto->name);
        $gameType->setGroup($dto->group);
        $gameType->setIsOfficial($dto->isOfficial);
        $oldImage = $gameType->getImage();
        $gameType->setImage($dto->image);
        $gameType->setUpdatedAt();

        $this->entityManager->flush();

        if ($oldImage && $dto->image !== $oldImage) {
            $this->bus->dispatch(new DeleteImageMessage($oldImage->getFilePath()));
        }

        $this->dispatcher->dispatch(new GameTypeUpdatedEvent($gameType));

        return $gameType;
    }

    public function delete(GameType $gameType): void
    {
        $image = $gameType->getImage();

        $gameType->setImage(null);

        if ($image) {
            $this->bus->dispatch(new DeleteImageMessage($image->getFilePath()));
        }

        $this->dispatcher->dispatch(new GameTypeDeletedEvent($gameType));

        $this->entityManager->remove($gameType);
        $this->entityManager->flush();
    }

    public function detachImage(GameType $gameType): void
    {
        $image = $gameType->getImage();

        $gameType->setImage(null);

        $this->entityManager->persist($gameType);
        $this->entityManager->flush();

        $this->bus->dispatch(new DeleteImageMessage($image->getFilePath()));
    }

    public function createDto(GameType $gameType): GameTypeDto
    {
        return $this->factory->createDto($gameType);
    }
}
