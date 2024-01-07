<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Image;
use App\Service\ImageManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

use function getimagesize;

/**
 * @method Image|null find($id, $lockMode = null, $lockVersion = null)
 * @method Image|null findOneBy(array $criteria, array $orderBy = null)
 * @method Image[]    findAll()
 * @method Image[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly ImageManager $imageManager)
    {
        parent::__construct($registry, Image::class);
    }

    public function createFromUpload($upload): ?Image
    {
        return $this->createFromPath($upload->getPathname());
    }

    public function createFromPath(string $source): ?Image
    {
        $fileName = $this->imageManager->getFileName($source);
        $filePath = $this->imageManager->getFilePath($source);

        [$width, $height] = @getimagesize($source);
        $image            = new Image($fileName, $filePath, $width, $height);

        if (!$image->getWidth() || !$image->getHeight()) {
            [$width, $height] = @getimagesize($source);
            $image->setDimensions($width, $height);
        }

        try {
            $this->imageManager->store($source, $filePath);
        } catch (Exception $e) {
            return null;
        }

        return $image;
    }
}
