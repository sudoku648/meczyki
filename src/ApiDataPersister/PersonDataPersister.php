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
    private PersonManager $manager;
    private PersonFactory $factory;
    private PersonRepository $repository;

    public function __construct(
        PersonManager $manager,
        PersonFactory $factory,
        PersonRepository $repository
    )
    {
        $this->manager    = $manager;
        $this->factory    = $factory;
        $this->repository = $repository;
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
