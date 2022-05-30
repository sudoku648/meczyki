<?php

declare(strict_types=1);

namespace App\ApiDataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Dto\MatchGameDto;
use App\Factory\MatchGameFactory;
use App\Repository\MatchGameRepository;
use App\Service\MatchGameManager;
use Symfony\Component\Security\Core\Security;

final class MatchGameDataPersister implements ContextAwareDataPersisterInterface
{
    private MatchGameManager $manager;
    private MatchGameFactory $factory;
    private MatchGameRepository $repository;
    private Security $security;

    public function __construct(
        MatchGameManager $manager,
        MatchGameFactory $factory,
        MatchGameRepository $repository,
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
        return $data instanceof MatchGameDto;
    }

    public function persist($data, array $context = []): MatchGameDto
    {
        if ($id = $data->getId()) {
            $matchGame = $this->repository->find($id);

            return $this->factory->createDto($this->manager->edit($matchGame, $data));
        }

        return $this->factory->createDto(
            $this->manager->create($data, $this->security->getToken()->getUser())
        );
    }

    public function remove($data, array $context = [])
    {
    }
}
