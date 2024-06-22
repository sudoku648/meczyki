<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Frontend\Controller;

use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;
use Sudoku648\Meczyki\GameType\Domain\Service\GameTypeManagerInterface;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\GameTypeVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Enums\FlashType;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Traits\RedirectTrait;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

use function str_replace;

final class GameTypeDeleteImageController extends AbstractController
{
    use RedirectTrait;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly GameTypeManagerInterface $manager,
    ) {
    }

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
                        'message' => $this->translator->trans(
                            id: 'Image has been deleted.',
                            domain: 'GameType',
                        ),
                        'label'   => FlashType::SUCCESS->value,
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
