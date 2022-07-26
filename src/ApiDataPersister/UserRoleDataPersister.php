<?php

declare(strict_types=1);

namespace App\ApiDataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Dto\UserRoleDto;
use App\Factory\UserRoleFactory;
use App\Repository\UserRoleRepository;
use App\Service\UserRoleManager;
use Symfony\Component\Security\Core\Security;

final class UserRoleDataPersister implements ContextAwareDataPersisterInterface
{
    private UserRoleManager $manager;
    private UserRoleFactory $factory;
    private UserRoleRepository $repository;
    private Security $security;

    public function __construct(
        UserRoleManager $manager,
        UserRoleFactory $factory,
        UserRoleRepository $repository,
        Security $security
    )
    {
        $this->manager    = $manager;
        $this->factory    = $factory;
        $this->repository = $repository;
        $this->security   = $security;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof UserRoleDto;
    }

    public function persist($data, array $context = []): UserRoleDto
    {
        if ($id = $data->getId()) {
            $userRole = $this->repository->find($id);

            return $this->factory->createDto($this->manager->edit($userRole, $data));
        }

        return $this->factory->createDto(
            $this->manager->create($data)
        );
    }

    public function remove($data, array $context = [])
    {
    }
}
