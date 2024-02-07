<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Infrastructure\Service;

use Doctrine\ORM\EntityManagerInterface;
use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;
use Sudoku648\Meczyki\GameType\Domain\Event\GameTypeCreatedEvent;
use Sudoku648\Meczyki\GameType\Domain\Event\GameTypeDeletedEvent;
use Sudoku648\Meczyki\GameType\Domain\Event\GameTypeUpdatedEvent;
use Sudoku648\Meczyki\GameType\Domain\Service\GameTypeManagerInterface;
use Sudoku648\Meczyki\GameType\Domain\ValueObject\GameTypeName;
use Sudoku648\Meczyki\GameType\Frontend\Dto\GameTypeDto;
use Sudoku648\Meczyki\GameType\Frontend\Factory\GameTypeFactory;
use Sudoku648\Meczyki\Image\Domain\Message\DeleteImageMessage;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class GameTypeManager implements GameTypeManagerInterface
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $messageBus,
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
        $gameType->setName(GameTypeName::fromString($dto->name));
        $gameType->setIsOfficial(true === $dto->isOfficial);
        $oldImage = $gameType->getImage();
        $gameType->setImage($dto->image);
        $gameType->setUpdatedAt();

        $this->entityManager->flush();

        if ($oldImage && $dto->image !== $oldImage) {
            $this->messageBus->dispatch(new DeleteImageMessage($oldImage->getFilePath()));
        }

        $this->dispatcher->dispatch(new GameTypeUpdatedEvent($gameType));

        return $gameType;
    }

    public function delete(GameType $gameType): void
    {
        $image = $gameType->getImage();

        $gameType->setImage(null);

        if ($image) {
            $this->messageBus->dispatch(new DeleteImageMessage($image->getFilePath()));
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

        $this->messageBus->dispatch(new DeleteImageMessage($image->getFilePath()));
    }

    public function createDto(GameType $gameType): GameTypeDto
    {
        return $this->factory->createDto($gameType);
    }
}
