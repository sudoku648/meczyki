<?php

declare(strict_types=1);

namespace App\Controller\GameType;

use App\Entity\GameType;
use App\Message\Flash\GameType\GameTypeDeletedImageFlashMessage;
use App\Security\Voter\GameTypeVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GameTypeDeleteImageController extends GameTypeAbstractController
{
    #[ParamConverter('gameType', options: ['mapping' => ['game_type_id' => 'id']])]
    public function __invoke(GameType $gameType, Request $request): Response
    {
        $this->denyAccessUnlessGranted(GameTypeVoter::DELETE_IMAGE, $gameType);

        $this->manager->detachImage($gameType);

        if ($request->isXmlHttpRequest()) {
            $flash = new GameTypeDeletedImageFlashMessage($gameType->getId());

            $message = \str_replace(
                ["\r\n", "\n", "\r", '"'],
                [' ', ' ', ' ', "'"],
                $this->renderView(
                    '_flash_alert.html.twig',
                    [
                        'message' => $flash->getMessage(),
                        'label'   => 'success',
                    ]
                )
            );

            return new JsonResponse(
                [
                    'message' => $message,
                ]
            );
        }

        return $this->redirectToGameTypesList();
    }
}
