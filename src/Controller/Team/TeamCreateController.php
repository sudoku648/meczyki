<?php

declare(strict_types=1);

namespace App\Controller\Team;

use App\Entity\Club;
use App\Form\TeamType;
use App\Security\Voter\TeamVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamCreateController extends TeamAbstractController
{
    public function __invoke(
        #[MapEntity(mapping: ['club_id' => 'id'])] Club $club,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(TeamVoter::CREATE);

        $this->breadcrumbs
            ->addItem(
                $club->getName(),
                $this->router->generate(
                    'club_single',
                    [
                        'club_id' => $club->getId(),
                    ]
                )
            )
            ->addItem(
                'Dodaj drużynę',
                $this->router->generate(
                    'team_create',
                    [
                        'club_id' => $club->getId(),
                    ]
                )
            )
        ;

        $form = $this->createForm(TeamType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dto       = $form->getData();
            $dto->club = $club;

            $this->manager->create($dto);

            $this->addFlash('success', 'Drużyna została dodana.');

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToRefererOrHome($request);
            }

            return $this->redirectToTeamsList();
        }

        return $this->render(
            'team/new.html.twig',
            [
                'form' => $form->createView(),
            ],
            new Response(
                null,
                $form->isSubmitted() && !$form->isValid()
                    ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK
            )
        );
    }
}