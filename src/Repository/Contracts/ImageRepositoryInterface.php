<?php

declare(strict_types=1);

namespace App\Repository\Contracts;

use App\Entity\Image;

interface ImageRepositoryInterface
{
    public function createFromUpload($upload): ?Image;

    public function createFromPath(string $source): ?Image;
}
