<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Image\Frontend\Factory;

use Sudoku648\Meczyki\Image\Domain\Entity\Image;
use Sudoku648\Meczyki\Image\Frontend\Dto\ImageDto;

class ImageFactory
{
    public function createDto(Image $image): ImageDto
    {
        $dto = new ImageDto();

        return $dto->create(
            $image->getFilePath(),
            $image->getWidth(),
            $image->getHeight(),
            $image->getId()
        );
    }
}
