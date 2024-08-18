<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Frontend\Controller;

use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\MatchGame\Domain\Service\MatchGameManagerInterface;
use Sudoku648\Meczyki\MatchGame\Frontend\Factory\MatchGameDtoFactory;
use Sudoku648\Meczyki\MatchGame\Frontend\Form\MatchGameType;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\MatchGameVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Enums\FlashType;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Traits\RedirectTrait;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class MatchGameEditController extends AbstractController
{
    use RedirectTrait;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly BreadcrumbBuilder $breadcrumbBuilder,
        private readonly MatchGameManagerInterface $manager,
    ) {
    }

    public function __invoke(
        #[MapEntity(mapping: ['match_game_id' => 'id'])] MatchGame $matchGame,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(MatchGameVoter::EDIT, $matchGame);

        $this->breadcrumbBuilder
            ->add('dashboard')
            ->add('match_games_list')
            ->add('match_game_edit', ['match_game_id' => $matchGame->getId()]);

        $dto = MatchGameDtoFactory::fromEntity($matchGame);

        $form = $this->createForm(MatchGameType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $matchGame = $this->manager->edit($matchGame, $dto);

            $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
                id: 'Match game has been updated.',
                domain: 'MatchGame',
            ));

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToEditMatchGame($matchGame);
            }

            return $this->redirectToMatchGamesList();
        }

        return $this->render(
            'match_game/edit.html.twig',
            [
                'form'      => $form->createView(),
                'matchGame' => $matchGame,
            ],
            new Response(
                null,
                $form->isSubmitted() && !$form->isValid()
                    ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK,
            ),
        );
    }
}
