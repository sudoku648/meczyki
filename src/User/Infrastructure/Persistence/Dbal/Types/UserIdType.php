<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Infrastructure\Persistence\Dbal\Types;

use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types\AbstractIdType;
use Sudoku648\Meczyki\User\Domain\ValueObject\UserId;

class UserIdType extends AbstractIdType
{
    public const NAME = 'UserId';

    public function getName(): string
    {
        return static::NAME;
    }

    protected function getValueObjectClassName(): string
    {
        return UserId::class;
    }
}
