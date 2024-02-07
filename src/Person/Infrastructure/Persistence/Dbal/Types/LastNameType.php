<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Infrastructure\Persistence\Dbal\Types;

use Sudoku648\Meczyki\Person\Domain\ValueObject\LastName;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types\AbstractStringType;

class LastNameType extends AbstractStringType
{
    public const NAME = 'LastName';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return LastName::class;
    }
}
