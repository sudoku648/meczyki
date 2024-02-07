<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Frontend\Controller;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Club\Frontend\Form\ClubType;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\ClubVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClubEditController extends ClubAbstractController
{
    public function __invoke(
        #[MapEntity(mapping: ['club_id' => 'id'])] Club $club,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(ClubVoter::EDIT, $club);

        $this->breadcrumbs->addItem(
            'Edytuj klub',
            $this->router->generate(
                'club_edit',
                [
                    'club_id' => $club->getId(),
                ]
            )
        );

        $dto = $this->manager->createDto($club);

        $form = $this->createForm(ClubType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $club = $this->manager->edit($club, $dto);

            $this->addFlash('success', 'Klub został zaktualizowany.');

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToEditClub($club);
            }

            return $this->redirectToClubsList();
        }

        return $this->render(
            'club/edit.html.twig',
            [
                'club' => $club,
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
