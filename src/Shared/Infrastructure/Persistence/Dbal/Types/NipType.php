<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Dbal\Types;

use Override;
use Sudoku648\Meczyki\Shared\Domain\ValueObject\Nip;

class NipType extends AbstractStringType
{
    public const NAME = 'Nip';

    public function getName(): string
    {
        return static::NAME;
    }

    #[Override]
    protected function getValueObjectClassName(): string
    {
        return Nip::class;
    }
}
