<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\UserDto;
use App\Entity\Person;
use App\Entity\User;
use App\Event\User\UserActivateEvent;
use App\Event\User\UserBindWithPersonEvent;
use App\Event\User\UserDeletedEvent;
use App\Event\User\UserUnbindWithPersonEvent;
use App\Factory\UserFactory;
use App\Service\Contracts\UserManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

readonly class UserManager implements UserManagerInterface
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private UserFactory $factory,
        private UserPasswordHasherInterface $passwordHasher,
        private TokenStorageInterface $tokenStorage,
        private RequestStack $requestStack,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function create(UserDto $dto, bool $isSuperAdmin = false): User
    {
        $user = new User($dto->username, '');
        $user->setOrRemoveSuperAdminRole(!$isSuperAdmin);

        $user->setPassword($this->passwordHasher->hashPassword($user, $dto->plainPassword));

        foreach ($dto->userRoles as $userRole) {
            $user->addUserRole($userRole);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function edit(User $user, UserDto $dto): User
    {
        $this->entityManager->beginTransaction();

        try {
            $user->setUsername($dto->username);

            if ($dto->plainPassword) {
                $user->setPassword($this->passwordHasher->hashPassword($user, $dto->plainPassword));
            }

            foreach ($dto->userRoles as $userRole) {
                $userRole->addUser($user);
            }

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Exception $e) {
            $this->entityManager->rollback();

            throw $e;
        }

        return $user;
    }

    public function activate(User $user): void
    {
        $user->activate();

        $this->entityManager->flush();

        $this->dispatcher->dispatch(new UserActivateEvent($user));
    }

    public function deactivate(User $user): void
    {
        $user->deactivate();

        $this->entityManager->flush();

        $this->dispatcher->dispatch(new UserActivateEvent($user));
    }

    public function delete(User $user): void
    {
        $this->dispatcher->dispatch(new UserDeletedEvent($user));

        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function bindWithPerson(User $user, Person $person): void
    {
        $user->bindWithPerson($person);

        $this->entityManager->flush();

        $this->dispatcher->dispatch(new UserBindWithPersonEvent($user));
    }

    public function unbindPerson(User $user): void
    {
        $user->unbindPerson();

        $this->entityManager->flush();

        $this->dispatcher->dispatch(new UserUnbindWithPersonEvent($user));
    }

    public function createDto(User $user): UserDto
    {
        return $this->factory->createDto($user);
    }

    public function logout(): void
    {
        $this->tokenStorage->setToken(null);
        $this->requestStack->getSession()->invalidate();
    }
}
