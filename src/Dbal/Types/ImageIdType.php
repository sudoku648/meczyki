<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\ImageId;

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
