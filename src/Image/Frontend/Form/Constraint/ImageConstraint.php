<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Image\Frontend\Form\Constraint;

use Sudoku648\Meczyki\Image\Infrastructure\Service\ImageManager;
use Symfony\Component\Validator\Constraints\Image;

final readonly class ImageConstraint
{
    public static function default(): Image
    {
        return new Image(
            [
                'detectCorrupted' => true,
                'groups'          => ['upload'],
                'maxSize'         => '12M',
                'mimeTypes'       => ImageManager::IMAGE_MIMETYPES,
            ]
        );
    }
}
