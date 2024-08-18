<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Domain\Service;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\User\Domain\Entity\User;
use Sudoku648\Meczyki\User\Frontend\Dto\CreateUserDto;
use Sudoku648\Meczyki\User\Frontend\Dto\UpdateUserDto;

interface UserManagerInterface
{
    public function create(CreateUserDto $dto, bool $isSuperAdmin = false): User;

    public function edit(User $user, UpdateUserDto $dto): User;

    public function activate(User $user): void;

    public function deactivate(User $user): void;

    public function delete(User $user): void;

    public function bindWithPerson(User $user, Person $person): void;

    public function unbindPerson(User $user): void;

    public function logout(): void;
}
