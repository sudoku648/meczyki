<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\TaxIncomeStakePercent;

class TaxIncomeStakePercentType extends AbstractIntPercentType
{
    public const NAME = 'TaxIncomeStakePercent';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return TaxIncomeStakePercent::class;
    }
}
