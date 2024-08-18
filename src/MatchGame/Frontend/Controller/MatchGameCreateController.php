<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Frontend\Controller;

use Sudoku648\Meczyki\MatchGame\Domain\Service\MatchGameManagerInterface;
use Sudoku648\Meczyki\MatchGame\Frontend\Dto\CreateMatchGameDto;
use Sudoku648\Meczyki\MatchGame\Frontend\Form\MatchGameType;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\MatchGameVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Enums\FlashType;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Traits\RedirectTrait;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class MatchGameCreateController extends AbstractController
{
    use RedirectTrait;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly BreadcrumbBuilder $breadcrumbBuilder,
        private readonly MatchGameManagerInterface $manager,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $this->denyAccessUnlessGranted(MatchGameVoter::CREATE);

        $this->breadcrumbBuilder
            ->add('dashboard')
            ->add('match_games_list')
            ->add('match_game_create');

        $form = $this->createForm(MatchGameType::class, new CreateMatchGameDto());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dto = $form->getData();

            $this->manager->create($dto, $this->getUserOrThrow());

            $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
                id: 'Match game has been added.',
                domain: 'MatchGame',
            ));

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToRefererOrHome($request);
            }

            return $this->redirectToMatchGamesList();
        }

        return $this->render(
            'match_game/new.html.twig',
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
