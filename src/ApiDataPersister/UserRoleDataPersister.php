<?php

declare(strict_types=1);

namespace App\ApiDataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Dto\UserRoleDto;
use App\Factory\UserRoleFactory;
use App\Repository\UserRoleRepository;
use App\Service\UserRoleManager;

final class UserRoleDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(
        private readonly UserRoleManager $manager,
        private readonly UserRoleFactory $factory,
        private readonly UserRoleRepository $repository
    ) {
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

        return $this->factory->createDto($this->manager->create($data));
    }

    public function remove($data, array $context = [])
    {
    }
}
