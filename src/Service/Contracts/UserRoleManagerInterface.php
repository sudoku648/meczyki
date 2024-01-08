<?php

declare(strict_types=1);

namespace App\Service\Contracts;

use App\Dto\UserRoleDto;
use App\Entity\UserRole;

interface UserRoleManagerInterface
{
    public function create(UserRoleDto $dto): UserRole;

    public function edit(UserRole $userRole, UserRoleDto $dto): UserRole;

    public function delete(UserRole $userRole): void;

    public function createDto(UserRole $userRole): UserRoleDto;
}
