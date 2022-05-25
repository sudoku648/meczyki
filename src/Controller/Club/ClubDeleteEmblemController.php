<?php

declare(strict_types=1);

namespace App\Controller\Club;

use App\Entity\Club;
use App\Message\Flash\Club\ClubDeletedEmblemFlashMessage;
use App\Security\Voter\ClubVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClubDeleteEmblemController extends ClubAbstractController
{
    #[ParamConverter('club', options: ['mapping' => ['club_id' => 'id']])]
    public function __invoke(Club $club, Request $request): Response
    {
        $this->denyAccessUnlessGranted(ClubVoter::DELETE_EMBLEM, $club);

        $this->manager->detachEmblem($club);

        if ($request->isXmlHttpRequest()) {
            $flash = new ClubDeletedEmblemFlashMessage($club->getId());

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

        return $this->redirectToRefererOrHome($request);
    }
}
