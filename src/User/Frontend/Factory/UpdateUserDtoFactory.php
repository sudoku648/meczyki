<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\Factory;

use Sudoku648\Meczyki\User\Domain\Entity\User;
use Sudoku648\Meczyki\User\Frontend\Dto\UpdateUserDto;

class UpdateUserDtoFactory
{
    public static function fromEntity(User $user): UpdateUserDto
    {
        return new UpdateUserDto(
            $user->getId(),
            $user->getUsername()->getValue(),
            isActive: $user->isActive(),
        );
    }
}
