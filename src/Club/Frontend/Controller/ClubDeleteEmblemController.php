<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Frontend\Controller;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Club\Domain\Service\ClubManagerInterface;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\ClubVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Enums\FlashType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

use function str_replace;

final class ClubDeleteEmblemController extends AbstractController
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly ClubManagerInterface $manager,
    ) {
    }

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
                        'message' => $this->translator->trans(
                            id: 'Emblem has been deleted.',
                            domain: 'Club',
                        ),
                        'label'   => FlashType::SUCCESS->value,
                    ]
                )
            );

            return new JsonResponse([
                'message' => $message,
            ]);
        }

        return $this->redirectToRefererOrHome($request);
    }
}
