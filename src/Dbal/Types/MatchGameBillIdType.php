<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\MatchGameBillId;

class MatchGameBillIdType extends AbstractIdType
{
    public const NAME = 'MatchGameBillId';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return MatchGameBillId::class;
    }
}
