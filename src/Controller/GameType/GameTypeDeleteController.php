<?php

declare(strict_types=1);

namespace App\Controller\GameType;

use App\Entity\GameType;
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

        $this->manager->delete($gameType);

        return $this->redirectToRefererOrHome($request);
    }
}
