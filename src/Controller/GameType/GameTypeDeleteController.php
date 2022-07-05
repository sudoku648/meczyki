<?php

declare(strict_types=1);

namespace App\Controller\GameType;

use App\Entity\GameType;
use App\Message\Flash\GameType\GameTypeDeletedBatchFlashMessage;
use App\Message\Flash\GameType\GameTypeDeletedFlashMessage;
use App\Message\Flash\GameType\GameTypeNotAllDeletedBatchFlashMessage;
use App\Repository\GameTypeRepository;
use App\Security\Voter\GameTypeVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GameTypeDeleteController extends GameTypeAbstractController
{
    #[ParamConverter('gameType', options: ['mapping' => ['game_type_id' => 'id']])]
    public function delete(GameType $gameType, Request $request): Response
    {
        $this->denyAccessUnlessGranted(GameTypeVoter::DELETE, $gameType);

        $this->validateCsrf('game_type_delete', $request->request->get('_token'));

        $this->flash(new GameTypeDeletedFlashMessage($gameType->getId()));

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
            $this->flash(new GameTypeNotAllDeletedBatchFlashMessage(), 'warning');
        } else {
            $this->flash(new GameTypeDeletedBatchFlashMessage());
        }

        return $this->redirectToGameTypesList();
    }
}
