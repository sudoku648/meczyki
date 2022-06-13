<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\UserDto;
use App\Entity\User;
use App\Factory\Contracts\ContentFactoryInterface;

class UserFactory implements ContentFactoryInterface
{
    public function createFromDto(UserDto $dto): User
    {
        return new User(
            $dto->username,
            $dto->plainPassword
        );
    }

    public function createDto(User $user): UserDto
    {
        $dto = new UserDto();

        $dto->username  = $user->getUsername();
        $dto->isActive  = $user->isActive();
        $dto->person    = $user->getPerson();
        $dto->createdAt = $user->getCreatedAt();
        $dto->setId($user->getId());

        return $dto;
    }
}
