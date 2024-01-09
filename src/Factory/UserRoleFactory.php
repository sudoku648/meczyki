<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\UserRoleDto;
use App\Entity\UserRole;

class UserRoleFactory
{
    public function createFromDto(UserRoleDto $dto): UserRole
    {
        return new UserRole(
            $dto->name,
            $dto->permissions ?? []
        );
    }

    public function createDto(UserRole $userRole): UserRoleDto
    {
        $dto = new UserRoleDto();

        $dto->name         = $userRole->getName();
        $dto->permissions  = $userRole->getPermissions();
        $dto->createdAt    = $userRole->getCreatedAt();
        $dto->createdAtAgo = $userRole->getCreatedAtAgo();
        $dto->updatedAt    = $userRole->getUpdatedAt();
        $dto->updatedAtAgo = $userRole->getUpdatedAtAgo();
        $dto->setId($userRole->getId());

        return $dto;
    }
}
