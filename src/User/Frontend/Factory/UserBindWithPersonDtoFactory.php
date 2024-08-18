<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\Factory;

use Sudoku648\Meczyki\User\Domain\Entity\User;
use Sudoku648\Meczyki\User\Frontend\Dto\UserBindWithPersonDto;

class UserBindWithPersonDtoFactory
{
    public static function fromEntity(User $user): UserBindWithPersonDto
    {
        return new UserBindWithPersonDto(
            $user->getPerson(),
        );
    }
}
