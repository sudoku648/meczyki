<?php

declare(strict_types=1);

namespace App\Entity;

use InvalidArgumentException;

class Image
{
    private string $id;

    private string $filePath;

    private string $fileName;

    private ?int $width;

    private ?int $height;

    public function __construct(
        string $fileName,
        string $filePath,
        ?int $width,
        ?int $height,
    ) {
        $this->filePath = $filePath;
        $this->fileName = $fileName;

        $this->setDimensions($width, $height);
    }

    public function setDimensions(?int $width, ?int $height): void
    {
        if (null !== $width && $width <= 0) {
            throw new InvalidArgumentException('$width must be NULL or > 0');
        }

        if (null !== $height && $height <= 0) {
            throw new InvalidArgumentException('$height must be NULL or > 0');
        }

        if (($width && $height) || (!$width && !$height)) {
            $this->width  = $width;
            $this->height = $height;
        } else {
            throw new InvalidArgumentException('$width and $height must both be set or NULL');
        }
    }

    public function getId(): string
    {
        return $this->id;
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

    public function __toString(): string
    {
        return $this->fileName;
    }

    public function __sleep()
    {
        return [];
    }
}
