<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Image\Domain\Persistence;

use Sudoku648\Meczyki\Image\Domain\Entity\Image;

interface ImageRepositoryInterface
{
    public function createFromUpload($upload): ?Image;

    public function createFromPath(string $source): ?Image;
}
