<?php

declare(strict_types=1);

namespace App\Controller\Team;

use App\Entity\Club;
use App\Entity\Team;
use App\Form\TeamType;
use App\Security\Voter\TeamVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamEditController extends TeamAbstractController
{
    public function __invoke(
        #[MapEntity(mapping: ['club_id' => 'id'])] Club $club,
        #[MapEntity(mapping: ['team_id' => 'id'])] Team $team,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(TeamVoter::EDIT, $team);

        $this->breadcrumbs->addItem(
            'Edytuj drużynę',
            $this->router->generate(
                'team_edit',
                [
                    'club_id' => $club->getId(),
                    'team_id' => $team->getId(),
                ]
            )
        );

        $dto = $this->manager->createDto($team);

        $form = $this->createForm(TeamType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $team = $this->manager->edit($team, $dto);

            $this->addFlash('success', 'Drużyna została zaktualizowana.');

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToEditTeam($team);
            }

            return $this->redirectToTeamsList();
        }

        return $this->render(
            'team/edit.html.twig',
            [
                'form' => $form->createView(),
                'team' => $team,
            ],
            new Response(
                null,
                $form->isSubmitted() && !$form->isValid()
                    ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK
            )
        );
    }
}