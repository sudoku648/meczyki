<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\BaseEquivalentPercent;

class BaseEquivalentPercentType extends AbstractIntPercentType
{
    public const NAME = 'BaseEquivalentPercent';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return BaseEquivalentPercent::class;
    }
}
