<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Frontend\Controller;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Club\Domain\Event\ClubHasBeenSeenEvent;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\ClubVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Response;

class ClubSingleController extends ClubAbstractController
{
    public function __invoke(
        #[MapEntity(mapping: ['club_id' => 'id'])] Club $club,
    ): Response {
        $this->denyAccessUnlessGranted(ClubVoter::SHOW, $club);

        $this->breadcrumbs->addItem(
            $club->getName(),
            $this->router->generate(
                'club_single',
                [
                    'club_id' => $club->getId(),
                ]
            ),
            [],
            false
        );

        $this->dispatcher->dispatch((new ClubHasBeenSeenEvent($club)));

        return $this->render(
            'club/single.html.twig',
            [
                'club' => $club,
            ]
        );
    }
}
