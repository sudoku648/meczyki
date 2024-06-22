<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Infrastructure\Persistence\Dbal\Types\Bill;

use Override;
use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\Bill\TaxIncomeStakePercent;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types\AbstractIntPercentType;

class TaxIncomeStakePercentType extends AbstractIntPercentType
{
    public const NAME = 'TaxIncomeStakePercent';

    public function getName(): string
    {
        return static::NAME;
    }

    #[Override]
    protected function getValueObjectClassName(): string
    {
        return TaxIncomeStakePercent::class;
    }
}
