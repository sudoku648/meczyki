<?php

declare(strict_types=1);

namespace App\Controller\Club;

use App\Entity\Club;
use App\Form\ClubType;
use App\Security\Voter\ClubVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClubEditController extends ClubAbstractController
{
    #[ParamConverter('club', options: ['mapping' => ['club_id' => 'id']])]
    public function __invoke(Club $club, Request $request): Response
    {
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

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToEditClub($club);
            }

            return $this->redirectToRoute(
                'clubs_front',
                [],
                Response::HTTP_SEE_OTHER
            );
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