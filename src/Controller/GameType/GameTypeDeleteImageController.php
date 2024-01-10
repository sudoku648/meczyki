<?php

declare(strict_types=1);

namespace App\Controller\GameType;

use App\Entity\GameType;
use App\Security\Voter\GameTypeVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function str_replace;

class GameTypeDeleteImageController extends GameTypeAbstractController
{
    public function __invoke(
        #[MapEntity(mapping: ['game_type_id' => 'id'])] GameType $gameType,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(GameTypeVoter::DELETE_IMAGE, $gameType);

        $this->manager->detachImage($gameType);

        if ($request->isXmlHttpRequest()) {
            $message = str_replace(
                ["\r\n", "\n", "\r", '"'],
                [' ', ' ', ' ', "'"],
                $this->renderView(
                    '_flash_alert.html.twig',
                    [
                        'message' => 'Obrazek został usunięty.',
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