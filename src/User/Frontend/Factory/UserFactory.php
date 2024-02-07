<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\Factory;

use Sudoku648\Meczyki\User\Domain\Entity\User;
use Sudoku648\Meczyki\User\Domain\ValueObject\Username;
use Sudoku648\Meczyki\User\Frontend\Dto\UserDto;

class UserFactory
{
    public function createFromDto(UserDto $dto): User
    {
        return new User(
            Username::fromString($dto->username),
            $dto->plainPassword,
        );
    }

    public function createDto(User $user): UserDto
    {
        $dto = new UserDto();

        $dto->username = $user->getUsername();
        $dto->isActive = $user->isActive();
        $dto->person   = $user->getPerson();
        $dto->setId($user->getId());

        return $dto;
    }
}
