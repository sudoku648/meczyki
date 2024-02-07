<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Image\Frontend\Dto;

use Sudoku648\Meczyki\Image\Domain\ValueObject\ImageId;

class ImageDto
{
    private ?ImageId $id = null;

    public ?string $filePath = null;

    public ?int $width = null;

    public ?int $height = null;

    public function create(
        string $filePath,
        ?int $width = null,
        ?int $height = null,
        ?ImageId $id = null,
    ): self {
        $this->id       = $id;
        $this->filePath = $filePath;
        $this->width    = $width;
        $this->height   = $height;

        return $this;
    }

    public function getId(): ?ImageId
    {
        return $this->id;
    }
}
