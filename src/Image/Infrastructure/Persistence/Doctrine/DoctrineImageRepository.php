<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Image\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sudoku648\Meczyki\Image\Domain\Entity\Image;
use Sudoku648\Meczyki\Image\Domain\Persistence\ImageRepositoryInterface;
use Sudoku648\Meczyki\Image\Domain\Service\ImageManagerInterface;

use function getimagesize;

/**
 * @method Image|null find($id, $lockMode = null, $lockVersion = null)
 * @method Image|null findOneBy(array $criteria, array $orderBy = null)
 * @method Image[]    findAll()
 * @method Image[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineImageRepository extends ServiceEntityRepository implements ImageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, private readonly ImageManagerInterface $imageManager)
    {
        parent::__construct($registry, Image::class);
    }

    public function persist(Image $image): void
    {
        $this->_em->persist($image);
        $this->_em->flush();
    }

    public function remove(Image $image): void
    {
        try {
            $this->_em->beginTransaction();

            $this->_em->remove($image);
            $this->_em->flush();

            $this->_em->commit();
        } catch (Exception $e) {
            $this->_em->rollback();
            $this->registry->resetManager();

            throw $e;
        }
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
