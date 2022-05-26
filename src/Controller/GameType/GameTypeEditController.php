<?php

declare(strict_types=1);

namespace App\Controller\GameType;

use App\Entity\GameType;
use App\Form\GameTypeType;
use App\Security\Voter\GameTypeVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GameTypeEditController extends GameTypeAbstractController
{
    #[ParamConverter('gameType', options: ['mapping' => ['game_type_id' => 'id']])]
    public function __invoke(GameType $gameType, Request $request): Response
    {
        $this->denyAccessUnlessGranted(GameTypeVoter::EDIT, $gameType);

        $this->breadcrumbs->addItem(
            'Edytuj typ rozgrywek',
            $this->router->generate(
                'game_type_edit',
                [
                    'game_type_id' => $gameType->getId(),
                ]
            )
        );

        $dto = $this->manager->createDto($gameType);

        $form = $this->createForm(GameTypeType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gameType = $this->manager->edit($gameType, $dto);

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToEditGameType($gameType);
            }

            return $this->redirectToRoute(
                'game_types_front',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render(
            'game_type/edit.html.twig',
            [
                'form'     => $form->createView(),
                'gameType' => $gameType,
            ],
            new Response(
                null,
                $form->isSubmitted() && !$form->isValid()
                    ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK
            )
        );
    }
}
