<?php

declare(strict_types=1);

namespace App\Controller\Club;

use App\Entity\Club;
use App\Event\Club\ClubHasBeenSeenEvent;
use App\Security\Voter\ClubVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Response;

class ClubSingleController extends ClubAbstractController
{
    public function __invoke(
        #[MapEntity(mapping: ['club_id' => 'id'])] Club $club
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