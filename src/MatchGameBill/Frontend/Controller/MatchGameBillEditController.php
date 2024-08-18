<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Frontend\Controller;

use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\MatchGameBill\Domain\Entity\MatchGameBill;
use Sudoku648\Meczyki\MatchGameBill\Domain\Service\MatchGameBillManagerInterface;
use Sudoku648\Meczyki\MatchGameBill\Frontend\Factory\UpdateMatchGameBillDtoFactory;
use Sudoku648\Meczyki\MatchGameBill\Frontend\Form\MatchGameBillType;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\MatchGameBillVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Enums\FlashType;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class MatchGameBillEditController extends AbstractController
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly BreadcrumbBuilder $breadcrumbBuilder,
        private readonly MatchGameBillManagerInterface $manager,
    ) {
    }

    public function __invoke(
        #[MapEntity(mapping: ['match_game_id' => 'id'])] MatchGame $matchGame,
        #[MapEntity(mapping: ['match_game_bill_id' => 'id'])] MatchGameBill $matchGameBill,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(MatchGameBillVoter::EDIT, $matchGameBill);

        $this->breadcrumbBuilder
            ->add('dashboard')
            ->add('match_games_list')
            ->add('match_game_single', ['match_game_id' => $matchGame->getId()])
            ->add('match_game_bill_edit', [
                'match_game_id'      => $matchGame->getId(),
                'match_game_bill_id' => $matchGameBill->getId(),
            ]);

        $dto = UpdateMatchGameBillDtoFactory::fromEntity($matchGameBill);

        $form = $this->createForm(MatchGameBillType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $matchGameBill = $this->manager->edit($matchGameBill, $dto);

            $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
                id: 'Match game bill has been updated.',
                domain: 'MatchGameBill',
            ));

            return $this->redirectToRoute(
                'match_game_bill_single',
                [
                    'match_game_id'      => $matchGame->getId(),
                    'match_game_bill_id' => $matchGameBill->getId(),
                ],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render(
            'match_game_bill/edit.html.twig',
            [
                'form'          => $form->createView(),
                'matchGame'     => $matchGame,
                'matchGameBill' => $matchGameBill,
            ],
            new Response(
                null,
                $form->isSubmitted() && !$form->isValid()
                    ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK,
            ),
        );
    }
}
