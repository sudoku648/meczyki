<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Infrastructure\Persistence\Dbal\Types\Bill;

use Override;
use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\Bill\TaxDeductibleStakePercent;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types\AbstractIntPercentType;

class TaxDeductibleStakePercentType extends AbstractIntPercentType
{
    public const NAME = 'TaxDeductibleStakePercent';

    public function getName(): string
    {
        return static::NAME;
    }

    #[Override]
    protected function getValueObjectClassName(): string
    {
        return TaxDeductibleStakePercent::class;
    }
}
