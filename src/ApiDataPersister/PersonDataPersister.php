<?php

declare(strict_types=1);

namespace App\ApiDataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Dto\PersonDto;
use App\Factory\PersonFactory;
use App\Repository\PersonRepository;
use App\Service\PersonManager;

final class PersonDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(
        private readonly PersonManager $manager,
        private readonly PersonFactory $factory,
        private readonly PersonRepository $repository
    ) {
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof PersonDto;
    }

    public function persist($data, array $context = []): PersonDto
    {
        if ($id = $data->getId()) {
            $person = $this->repository->find($id);

            return $this->factory->createDto($this->manager->edit($person, $data));
        }

        return $this->factory->createDto($this->manager->create($data));
    }

    public function remove($data, array $context = [])
    {
    }
}
