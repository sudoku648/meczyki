<?php

declare(strict_types=1);

namespace App\ApiDataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Dto\TeamDto;
use App\Factory\TeamFactory;
use App\Repository\TeamRepository;
use App\Service\TeamManager;

final class TeamDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(
        private readonly TeamManager $manager,
        private readonly TeamFactory $factory,
        private readonly TeamRepository $repository
    ) {
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof TeamDto;
    }

    public function persist($data, array $context = []): TeamDto
    {
        if ($id = $data->getId()) {
            $team = $this->repository->find($id);

            return $this->factory->createDto($this->manager->edit($team, $data));
        }

        return $this->factory->createDto($this->manager->create($data));
    }

    public function remove($data, array $context = [])
    {
    }
}
