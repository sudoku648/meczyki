<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Frontend\Controller;

use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\GameTypeVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Response;

final class GameTypeSingleController extends AbstractController
{
    public function __construct(
        private readonly BreadcrumbBuilder $breadcrumbBuilder,
    ) {
    }

    public function __invoke(
        #[MapEntity(mapping: ['game_type_id' => 'id'])] GameType $gameType,
    ): Response {
        $this->denyAccessUnlessGranted(GameTypeVoter::SHOW, $gameType);

        $this->breadcrumbBuilder
            ->add('dashboard')
            ->add('game_types_list')
            ->add('game_type_single', ['game_type_id' => $gameType->getId()]);

        return $this->render(
            'game_type/single.html.twig',
            [
                'gameType' => $gameType,
            ]
        );
    }
}
