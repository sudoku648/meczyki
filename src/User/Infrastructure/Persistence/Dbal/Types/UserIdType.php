<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Infrastructure\Persistence\Dbal\Types;

use Override;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types\AbstractIdType;
use Sudoku648\Meczyki\User\Domain\ValueObject\UserId;

class UserIdType extends AbstractIdType
{
    public const string NAME = 'UserId';

    public function getName(): string
    {
        return static::NAME;
    }

    #[Override]
    protected function getValueObjectClassName(): string
    {
        return UserId::class;
    }
}
