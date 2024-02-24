<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Image\Infrastructure\MessageHandler;

use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sudoku648\Meczyki\Image\Domain\Message\DeleteImageMessage;
use Sudoku648\Meczyki\Image\Domain\Persistence\ImageRepositoryInterface;
use Sudoku648\Meczyki\Image\Domain\Service\ImageManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
readonly class DeleteImageHandler
{
    public function __construct(
        private ManagerRegistry $managerRegistry,
        private ImageRepositoryInterface $imageRepository,
        private ImageManagerInterface $imageManager,
    ) {
    }

    public function __invoke(DeleteImageMessage $message)
    {
        $image = $this->imageRepository->findOneBy(['filePath' => $message->getPath(), ]);

        if ($image) {
            try {
                $this->imageRepository->remove($image);
            } catch (Exception $e) {
                return;
            }
        }

        $this->imageManager->remove($message->getPath());
    }
}
