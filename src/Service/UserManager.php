<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\UserDto;
use App\Entity\User;
use App\Event\User\UserActivateEvent;
use App\Event\User\UserDeletedEvent;
use App\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserManager
{
    private EventDispatcherInterface $dispatcher;
    private UserFactory $factory;
    private UserPasswordHasherInterface $passwordHasher;
    private TokenStorageInterface $tokenStorage;
    private RequestStack $requestStack;
    private EntityManagerInterface $entityManager;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        UserFactory $factory,
        UserPasswordHasherInterface $passwordHasher,
        TokenStorageInterface $tokenStorage,
        RequestStack $requestStack,
        EntityManagerInterface $entityManager
    )
    {
        $this->dispatcher     = $dispatcher;
        $this->factory        = $factory;
        $this->passwordHasher = $passwordHasher;
        $this->tokenStorage   = $tokenStorage;
        $this->requestStack   = $requestStack;
        $this->entityManager  = $entityManager;
    }

    public function create(UserDto $dto, bool $isSuperAdmin = false): User
    {
        $user = new User($dto->username, '');
        $user->setOrRemoveSuperAdminRole(!$isSuperAdmin);

        $user->setPassword($this->passwordHasher->hashPassword($user, $dto->plainPassword));

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

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
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
