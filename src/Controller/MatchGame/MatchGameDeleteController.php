<?php

declare(strict_types=1);

namespace App\Controller\MatchGame;

use App\Entity\MatchGame;
use App\Repository\MatchGameRepository;
use App\Security\Voter\MatchGameVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MatchGameDeleteController extends MatchGameAbstractController
{
    public function delete(
        #[MapEntity(mapping: ['match_game_id' => 'id'])] MatchGame $matchGame,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(MatchGameVoter::DELETE, $matchGame);

        $this->validateCsrf('match_game_delete', $request->request->get('_token'));

        $this->addFlash('success', 'Mecz został usunięty.');

        $this->manager->delete($matchGame);

        return $this->redirectToMatchGamesList();
    }

    public function deleteBatch(MatchGameRepository $repository, Request $request): Response
    {
        $this->denyAccessUnlessGranted(MatchGameVoter::DELETE_BATCH);

        $this->validateCsrf('match_game_delete_batch', $request->request->get('_token'));

        $matchGameIds = $request->request->all('matchGames');

        $notAllDeleted = false;
        foreach ($matchGameIds as $matchGameId) {
            $matchGame = $repository->find($matchGameId);
            if ($matchGame) {
                if ($this->isGranted(MatchGameVoter::DELETE, $matchGame)) {
                    $this->manager->delete($matchGame);

                    continue;
                }

                $notAllDeleted = true;
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