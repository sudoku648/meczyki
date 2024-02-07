<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Infrastructure\Persistence\Dbal\Types;

use Sudoku648\Meczyki\Person\Domain\ValueObject\PersonId;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types\AbstractIdType;

class PersonIdType extends AbstractIdType
{
    public const NAME = 'PersonId';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return PersonId::class;
    }
}
