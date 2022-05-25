<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\DeleteImageMessage;
use App\Repository\ImageRepository;
use App\Service\ImageManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeleteImageHandler implements MessageHandlerInterface
{
    private ImageRepository $imageRepository;
    private ImageManager $imageManager;
    private EntityManagerInterface $entityManager;
    private ManagerRegistry $managerRegistry;

    public function __construct(
        ImageRepository $imageRepository,
        ImageManager $imageManager,
        EntityManagerInterface $entityManager,
        ManagerRegistry $managerRegistry
    )
    {
        $this->imageRepository = $imageRepository;
        $this->imageManager    = $imageManager;
        $this->entityManager   = $entityManager;
        $this->managerRegistry = $managerRegistry;
    }

    public function __invoke(DeleteImageMessage $message)
    {
        $image = $this->imageRepository->findOneBy(['filePath' => $message->getPath(),]);

        if ($image) {
            try {
                $this->entityManager->beginTransaction();

                $this->entityManager->remove($image);
                $this->entityManager->flush();

                $this->entityManager->commit();
            } catch (\Exception $e) {
                $this->entityManager->rollback();
                $this->managerRegistry->resetManager();
                return;
            }
        }

        $this->imageManager->remove($message->getPath());
    }
}
