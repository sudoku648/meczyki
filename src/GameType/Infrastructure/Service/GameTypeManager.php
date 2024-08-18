<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Infrastructure\Service;

use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;
use Sudoku648\Meczyki\GameType\Domain\Persistence\GameTypeRepositoryInterface;
use Sudoku648\Meczyki\GameType\Domain\Service\GameTypeManagerInterface;
use Sudoku648\Meczyki\GameType\Domain\ValueObject\GameTypeName;
use Sudoku648\Meczyki\GameType\Frontend\Dto\GameTypeDto;
use Sudoku648\Meczyki\Image\Domain\Message\DeleteImageMessage;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class GameTypeManager implements GameTypeManagerInterface
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private GameTypeRepositoryInterface $repository,
    ) {
    }

    public function create(GameTypeDto $dto): GameType
    {
        $gameType = new GameType(
            GameTypeName::fromString($dto->name),
            $dto->isOfficial,
        );

        $this->repository->persist($gameType);

        return $gameType;
    }

    public function edit(GameType $gameType, GameTypeDto $dto): GameType
    {
        $gameType->setName(GameTypeName::fromString($dto->name));
        $gameType->setIsOfficial(true === $dto->isOfficial);
        $oldImage = $gameType->getImage();
        $gameType->setImage($dto->image);
        $gameType->setUpdatedAt();

        $this->repository->persist($gameType);

        if ($oldImage && $dto->image !== $oldImage) {
            $this->messageBus->dispatch(new DeleteImageMessage($oldImage->getFilePath()));
        }

        return $gameType;
    }

    public function delete(GameType $gameType): void
    {
        $image = $gameType->getImage();

        $gameType->setImage(null);

        if ($image) {
            $this->messageBus->dispatch(new DeleteImageMessage($image->getFilePath()));
        }

        $this->repository->remove($gameType);
    }

    public function detachImage(GameType $gameType): void
    {
        $image = $gameType->getImage();

        $gameType->setImage(null);

        $this->repository->persist($gameType);

        $this->messageBus->dispatch(new DeleteImageMessage($image->getFilePath()));
    }
}
