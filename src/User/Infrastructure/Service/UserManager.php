<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Infrastructure\Service;

use Exception;
use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\User\Domain\Entity\User;
use Sudoku648\Meczyki\User\Domain\Persistence\UserRepositoryInterface;
use Sudoku648\Meczyki\User\Domain\Service\UserManagerInterface;
use Sudoku648\Meczyki\User\Domain\ValueObject\Username;
use Sudoku648\Meczyki\User\Frontend\Dto\UserDto;
use Sudoku648\Meczyki\User\Frontend\Factory\UserDtoFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

readonly class UserManager implements UserManagerInterface
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private RequestStack $requestStack,
        private UserPasswordHasherInterface $passwordHasher,
        private UserDtoFactory $factory,
        private UserRepositoryInterface $repository,
    ) {
    }

    public function create(UserDto $dto, bool $isSuperAdmin = false): User
    {
        $user = new User(Username::fromString($dto->username), '');
        $user->setOrRemoveSuperAdminRole(!$isSuperAdmin);

        $user->setPassword($this->passwordHasher->hashPassword($user, $dto->plainPassword));

        $this->repository->persist($user);

        return $user;
    }

    public function edit(User $user, UserDto $dto): User
    {
        $user->setUsername(Username::fromString($dto->username));

        if ($dto->plainPassword) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $dto->plainPassword));
        }

        try {
            $this->repository->persist($user);
        } catch (Exception $e) {
        }

        return $user;
    }

    public function activate(User $user): void
    {
        $user->activate();

        $this->repository->persist($user);
    }

    public function deactivate(User $user): void
    {
        $user->deactivate();

        $this->repository->persist($user);
    }

    public function delete(User $user): void
    {
        $this->repository->remove($user);
    }

    public function bindWithPerson(User $user, Person $person): void
    {
        $user->bindWithPerson($person);

        $this->repository->persist($user);
    }

    public function unbindPerson(User $user): void
    {
        $user->unbindPerson();

        $this->repository->persist($user);
    }

    public function logout(): void
    {
        $this->tokenStorage->setToken(null);
        $this->requestStack->getSession()->invalidate();
    }
}
