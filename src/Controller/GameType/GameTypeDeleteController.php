<?php

declare(strict_types=1);

namespace App\Controller\GameType;

use App\Entity\GameType;
use App\Repository\GameTypeRepository;
use App\Security\Voter\GameTypeVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GameTypeDeleteController extends GameTypeAbstractController
{
    public function delete(
        #[MapEntity(mapping: ['game_type_id' => 'id'])] GameType $gameType,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(GameTypeVoter::DELETE, $gameType);

        $this->validateCsrf('game_type_delete', $request->request->get('_token'));

        $this->addFlash('success', 'Typ rozgrywek został usunięty.');

        $this->manager->delete($gameType);

        return $this->redirectToGameTypesList();
    }

    public function deleteBatch(GameTypeRepository $repository, Request $request): Response
    {
        $this->denyAccessUnlessGranted(GameTypeVoter::DELETE_BATCH);

        $this->validateCsrf('game_type_delete_batch', $request->request->get('_token'));

        $gameTypeIds = $request->request->all('gameTypes');

        $notAllDeleted = false;
        foreach ($gameTypeIds as $gameTypeId) {
            $gameType = $repository->find($gameTypeId);
            if ($gameType) {
                if ($this->isGranted(GameTypeVoter::DELETE, $gameType)) {
                    $this->manager->delete($gameType);

                    continue;
                }

                $notAllDeleted = true;
            }
        }

        if ($notAllDeleted) {
            $this->addFlash('warning', 'Nie wszystkie typy rozgrywek zostały usunięte.');
        } else {
            $this->addFlash('success', 'Typy rozgrywek zostały usunięte.');
        }

        return $this->redirectToGameTypesList();
    }
}