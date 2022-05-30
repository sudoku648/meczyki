<?php

declare(strict_types=1);

namespace App\ApiDataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Dto\UserDto;
use App\Factory\UserFactory;
use App\Repository\UserRepository;
use App\Service\UserManager;

final class UserDataPersister implements ContextAwareDataPersisterInterface
{
    private UserManager $manager;
    private UserFactory $factory;
    private UserRepository $repository;

    public function __construct(
        UserManager $manager,
        UserFactory $factory,
        UserRepository $repository
    )
    {
        $this->manager    = $manager;
        $this->factory    = $factory;
        $this->repository = $repository;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof UserDto;
    }

    public function persist($data, array $context = []): UserDto
    {
        if ($id = $data->getId()) {
            $user = $this->repository->find($id);

            return $this->factory->createDto($this->manager->edit($user, $data));
        }

        return $this->factory->createDto($this->manager->create($data));
    }

    public function remove($data, array $context = [])
    {
    }
}
