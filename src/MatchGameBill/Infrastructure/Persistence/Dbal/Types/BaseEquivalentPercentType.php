<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Infrastructure\Persistence\Dbal\Types;

use Override;
use Sudoku648\Meczyki\MatchGameBill\Domain\ValueObject\BaseEquivalentPercent;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types\AbstractIntPercentType;

class BaseEquivalentPercentType extends AbstractIntPercentType
{
    public const string NAME = 'BaseEquivalentPercent';

    public function getName(): string
    {
        return static::NAME;
    }

    #[Override]
    protected function getValueObjectClassName(): string
    {
        return BaseEquivalentPercent::class;
    }
}
