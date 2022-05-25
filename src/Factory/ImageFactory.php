<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\ImageDto;
use App\Entity\Image;

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
