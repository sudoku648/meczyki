<?php

declare(strict_types=1);

namespace App\Controller\Club;

use App\Entity\Club;
use App\Security\Voter\ClubVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function str_replace;

class ClubDeleteEmblemController extends ClubAbstractController
{
    public function __invoke(
        #[MapEntity(mapping: ['club_id' => 'id'])] Club $club,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(ClubVoter::DELETE_EMBLEM, $club);

        $this->manager->detachEmblem($club);

        if ($request->isXmlHttpRequest()) {
            $message = str_replace(
                ["\r\n", "\n", "\r", '"'],
                [' ', ' ', ' ', "'"],
                $this->renderView(
                    '_flash_alert.html.twig',
                    [
                        'message' => 'Herb został usunięty.',
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

        return $this->redirectToRefererOrHome($request);
    }
}