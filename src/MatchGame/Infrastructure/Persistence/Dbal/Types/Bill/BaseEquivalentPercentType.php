<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Infrastructure\Persistence\Dbal\Types\Bill;

use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\Bill\BaseEquivalentPercent;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types\AbstractIntPercentType;

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
