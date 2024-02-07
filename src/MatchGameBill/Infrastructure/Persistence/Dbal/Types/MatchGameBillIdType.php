<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Infrastructure\Persistence\Dbal\Types;

use Sudoku648\Meczyki\MatchGameBill\Domain\ValueObject\MatchGameBillId;
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
