<?php

declare(strict_types=1);

namespace App\Controller\MatchGame;

use App\Entity\MatchGame;
use App\Security\Voter\MatchGameVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MatchGameDeleteController extends MatchGameAbstractController
{
    #[ParamConverter('matchGame', options: ['mapping' => ['match_game_id' => 'id']])]
    public function delete(MatchGame $matchGame, Request $request): Response
    {
        $this->denyAccessUnlessGranted(MatchGameVoter::DELETE, $matchGame);

        $this->validateCsrf('match_game_delete', $request->request->get('_token'));

        $this->manager->delete($matchGame);

        return $this->redirectToMatchGamesList();
    }
}
