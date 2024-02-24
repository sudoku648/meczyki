<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Infrastructure\Persistence\Dbal\Types\Bill;

use Sudoku648\Meczyki\MatchGame\Domain\ValueObject\Bill\MatchGameBillId;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types\AbstractIdType;

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
