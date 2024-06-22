<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Frontend\Controller;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\ClubVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Response;

final class ClubSingleController extends AbstractController
{
    public function __construct(
        private readonly BreadcrumbBuilder $breadcrumbBuilder,
    ) {
    }

    public function __invoke(
        #[MapEntity(mapping: ['club_id' => 'id'])] Club $club,
    ): Response {
        $this->denyAccessUnlessGranted(ClubVoter::SHOW, $club);

        $this->breadcrumbBuilder
            ->add('dashboard')
            ->add('clubs_list')
            ->add('club_single', ['club_id' => $club->getId()]);

        return $this->render(
            'club/single.html.twig',
            [
                'club' => $club,
            ]
        );
    }
}
