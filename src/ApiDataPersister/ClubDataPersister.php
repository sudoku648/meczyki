<?php

declare(strict_types=1);

namespace App\ApiDataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Dto\ClubDto;
use App\Factory\ClubFactory;
use App\Repository\ClubRepository;
use App\Service\ClubManager;

final class ClubDataPersister implements ContextAwareDataPersisterInterface
{
    private ClubManager $manager;
    private ClubFactory $factory;
    private ClubRepository $repository;

    public function __construct(
        ClubManager $manager,
        ClubFactory $factory,
        ClubRepository $repository
    )
    {
        $this->manager    = $manager;
        $this->factory    = $factory;
        $this->repository = $repository;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof ClubDto;
    }

    public function persist($data, array $context = []): ClubDto
    {
        if ($id = $data->getId()) {
            $club = $this->repository->find($id);

            return $this->factory->createDto($this->manager->edit($club, $data));
        }

        return $this->factory->createDto($this->manager->create($data));
    }

    public function remove($data, array $context = [])
    {
    }
}