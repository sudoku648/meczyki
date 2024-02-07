<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Image\Infrastructure\Persistence\Dbal\Types;

use Sudoku648\Meczyki\Image\Domain\ValueObject\ImageId;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types\AbstractIdType;

class ImageIdType extends AbstractIdType
{
    public const NAME = 'ImageId';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return ImageId::class;
    }
}
