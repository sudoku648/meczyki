<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Frontend\Controller;

use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\MatchGame\Domain\Persistence\MatchGameRepositoryInterface;
use Sudoku648\Meczyki\MatchGame\Domain\Service\MatchGameManagerInterface;
use Sudoku648\Meczyki\MatchGame\Infrastructure\Persistence\Doctrine\DoctrineMatchGameRepository;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\MatchGameVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Traits\RedirectTrait;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MatchGameDeleteController extends AbstractController
{
    use RedirectTrait;

    public function __construct(
        private readonly MatchGameManagerInterface $manager,
    ) {
    }

    public function delete(
        #[MapEntity(mapping: ['match_game_id' => 'id'])] MatchGame $matchGame,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(MatchGameVoter::DELETE, $matchGame);

        $this->validateCsrf('match_game_delete', $request->request->get('_token'));

        $this->addFlash('success', 'Mecz został usunięty.');

        $this->manager->delete($matchGame);

        return $this->redirectToMatchGamesList();
    }

    /** @param DoctrineMatchGameRepository $repository */
    public function deleteBatch(MatchGameRepositoryInterface $repository, Request $request): Response
    {
        $this->denyAccessUnlessGranted(MatchGameVoter::DELETE_BATCH);

        $this->validateCsrf('match_game_delete_batch', $request->request->get('_token'));

        $matchGameIds = $request->request->all('matchGames');

        $notAllDeleted = false;
        foreach ($matchGameIds as $matchGameId) {
            $matchGame = $repository->find($matchGameId);
            if ($matchGame) {
                if (!$this->isGranted(MatchGameVoter::DELETE, $matchGame)) {
                    $notAllDeleted = true;

                    continue;
                }

                $this->manager->delete($matchGame);
            }
        }

        if ($notAllDeleted) {
            $this->addFlash('warning', 'Nie wszystkie mecze zostały usunięte.');
        } else {
            $this->addFlash('success', 'Mecze zostały usunięte.');
        }

        return $this->redirectToMatchGamesList();
    }
}
