<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Frontend\Controller;

use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\MatchGameBill\Domain\Entity\MatchGameBill;
use Sudoku648\Meczyki\MatchGameBill\Domain\Service\MatchGameBillManagerInterface;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\MatchGameBillVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Enums\FlashType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class MatchGameBillDeleteController extends AbstractController
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly MatchGameBillManagerInterface $manager,
    ) {
    }

    public function delete(
        #[MapEntity(mapping: ['match_game_id' => 'id'])] MatchGame $matchGame,
        #[MapEntity(mapping: ['match_game_bill_id' => 'id'])] MatchGameBill $matchGameBill,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(MatchGameBillVoter::DELETE, $matchGameBill);

        $this->validateCsrf('match_game_bill_delete', $request->request->get('_token'));

        $this->manager->delete($matchGameBill);

        $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
            id: 'Match game bill has been updated.',
            domain: 'MatchGame',
        ));

        return $this->redirectToSingleMatchGame($matchGame);
    }
}
