<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\UserRoleDto;
use App\Entity\User;
use App\Entity\UserRole;
use App\Event\UserRole\UserRoleCreatedEvent;
use App\Event\UserRole\UserRoleDeletedEvent;
use App\Event\UserRole\UserRoleUpdatedEvent;
use App\Factory\UserRoleFactory;
use App\Service\Contracts\ContentManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UserRoleManager implements ContentManagerInterface
{
    public function __construct(
        private readonly EventDispatcherInterface $dispatcher,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRoleFactory $factory
    ) {
    }

    public function create(UserRoleDto $dto): UserRole
    {
        $userRole = $this->factory->createFromDto($dto);

        $this->entityManager->persist($userRole);
        $this->entityManager->flush();

        $this->dispatcher->dispatch(new UserRoleCreatedEvent($userRole));

        return $userRole;
    }

    public function edit(UserRole $userRole, UserRoleDto $dto): UserRole
    {
        $userRole->setName($dto->name);
        $userRole->setPermissions($dto->permissions ?? []);
        $userRole->setUpdatedAt();

        $this->entityManager->flush();

        $this->dispatcher->dispatch(new UserRoleUpdatedEvent($userRole));

        return $userRole;
    }

    public function delete(UserRole $userRole): void
    {
        $this->dispatcher->dispatch(new UserRoleDeletedEvent($userRole));

        $this->entityManager->remove($userRole);
        $this->entityManager->flush();
    }

    public function createDto(UserRole $userRole): UserRoleDto
    {
        return $this->factory->createDto($userRole);
    }
}
