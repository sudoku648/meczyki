<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[ORM\UniqueConstraint(name: 'images_file_name_idx', columns: ['file_name'])]
class Image
{
    #[ORM\Column(type: Types::STRING)]
    private string $filePath;

    #[ORM\Column(type: Types::STRING, length: 180)]
    private string $fileName;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $width;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $height;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    public function __construct(
        string $fileName,
        string $filePath,
        ?int $width,
        ?int $height
    )
    {
        $this->filePath = $filePath;
        $this->fileName = $fileName;

        $this->setDimensions($width, $height);
    }

    public function setDimensions(?int $width, ?int $height): void
    {
        if (null !== $width && $width <= 0) {
            throw new \InvalidArgumentException('$width must be NULL or > 0');
        }

        if (null !== $height && $height <= 0) {
            throw new \InvalidArgumentException('$height must be NULL or > 0');
        }

        if (($width && $height) || (!$width && !$height)) {
            $this->width  = $width;
            $this->height = $height;
        } else {
            throw new \InvalidArgumentException('$width and $height must both be set or NULL');
        }
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->fileName;
    }

    public function __sleep()
    {
        return [];
    }
}
