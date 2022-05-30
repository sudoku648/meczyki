<?php

declare(strict_types=1);

namespace App\ApiDataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Dto\GameTypeDto;
use App\Factory\GameTypeFactory;
use App\Repository\GameTypeRepository;
use App\Service\GameTypeManager;

final class GameTypeDataPersister implements ContextAwareDataPersisterInterface
{
    private GameTypeManager $manager;
    private GameTypeFactory $factory;
    private GameTypeRepository $repository;

    public function __construct(
        GameTypeManager $manager,
        GameTypeFactory $factory,
        GameTypeRepository $repository
    )
    {
        $this->manager    = $manager;
        $this->factory    = $factory;
        $this->repository = $repository;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof GameTypeDto;
    }

    public function persist($data, array $context = []): GameTypeDto
    {
        if ($id = $data->getId()) {
            $gameType = $this->repository->find($id);

            return $this->factory->createDto($this->manager->edit($gameType, $data));
        }

        return $this->factory->createDto($this->manager->create($data));
    }

    public function remove($data, array $context = [])
    {
    }
}
