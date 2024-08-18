<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Frontend\Controller;

use Sudoku648\Meczyki\GameType\Domain\Service\GameTypeManagerInterface;
use Sudoku648\Meczyki\GameType\Frontend\Dto\CreateGameTypeDto;
use Sudoku648\Meczyki\GameType\Frontend\Form\GameTypeType;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\GameTypeVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Enums\FlashType;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Traits\RedirectTrait;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class GameTypeCreateController extends AbstractController
{
    use RedirectTrait;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly BreadcrumbBuilder $breadcrumbBuilder,
        private readonly GameTypeManagerInterface $manager,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $this->denyAccessUnlessGranted(GameTypeVoter::CREATE);

        $this->breadcrumbBuilder
            ->add('dashboard')
            ->add('game_types_list')
            ->add('game_type_create');

        $form = $this->createForm(GameTypeType::class, new CreateGameTypeDto());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dto = $form->getData();

            $this->manager->create($dto);

            $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
                id: 'Game type has been added.',
                domain: 'GameType',
            ));

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToRefererOrHome($request);
            }

            return $this->redirectToGameTypesList();
        }

        return $this->render(
            'game_type/new.html.twig',
            [
                'form' => $form->createView(),
            ],
            new Response(
                null,
                $form->isSubmitted() && !$form->isValid()
                    ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK,
            ),
        );
    }
}
