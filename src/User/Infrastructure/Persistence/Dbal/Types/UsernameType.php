<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Infrastructure\Persistence\Dbal\Types;

use Override;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types\AbstractStringType;
use Sudoku648\Meczyki\User\Domain\ValueObject\Username;

class UsernameType extends AbstractStringType
{
    public const NAME = 'Username';

    public function getName(): string
    {
        return static::NAME;
    }

    #[Override]
    protected function getValueObjectClassName(): string
    {
        return Username::class;
    }
}
