<?php

declare(strict_types=1);

namespace App\Controller\MatchGame;

use App\Entity\MatchGame;
use App\Event\MatchGame\MatchGameHasBeenSeenEvent;
use App\Security\Voter\MatchGameVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

class MatchGameSingleController extends MatchGameAbstractController
{
    #[ParamConverter('matchGame', options: ['mapping' => ['match_game_id' => 'id']])]
    public function __invoke(MatchGame $matchGame): Response
    {
        $this->denyAccessUnlessGranted(MatchGameVoter::SHOW, $matchGame);

        $this->breadcrumbs->addItem(
            $matchGame->getCompetitors(),
            $this->router->generate(
                'match_game_single',
                [
                    'match_game_id' => $matchGame->getId(),
                ]
            ),
            [],
            false
        );

        $this->dispatcher->dispatch((new MatchGameHasBeenSeenEvent($matchGame)));

        return $this->render(
            'match_game/single.html.twig',
            [
                'matchGame' => $matchGame,
            ]
        );
    }
}
