<?php

declare(strict_types=1);

namespace App\Dbal\Types;

use App\ValueObject\TaxDeductibleStakePercent;

class TaxDeductibleStakePercentType extends AbstractIntPercentType
{
    public const NAME = 'TaxDeductibleStakePercent';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return TaxDeductibleStakePercent::class;
    }
}
