<?php

declare(strict_types=1);

namespace App\Controller\Team;

use App\Entity\Club;
use App\Form\TeamType;
use App\Security\Voter\TeamVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamCreateController extends TeamAbstractController
{
    #[ParamConverter('club', options: ['mapping' => ['club_id' => 'id']])]
    public function __invoke(Club $club, Request $request): Response
    {
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

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToRefererOrHome($request);
            }

            return $this->redirectToRoute(
                'teams_front',
                [],
                Response::HTTP_SEE_OTHER
            );
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