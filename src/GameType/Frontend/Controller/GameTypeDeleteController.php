<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Frontend\Controller;

use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;
use Sudoku648\Meczyki\GameType\Domain\Persistence\GameTypeRepositoryInterface;
use Sudoku648\Meczyki\GameType\Domain\Service\GameTypeManagerInterface;
use Sudoku648\Meczyki\GameType\Infrastructure\Persistence\Doctrine\DoctrineGameTypeRepository;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\GameTypeVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Enums\FlashType;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Traits\RedirectTrait;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class GameTypeDeleteController extends AbstractController
{
    use RedirectTrait;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly GameTypeManagerInterface $manager,
    ) {
    }

    public function delete(
        #[MapEntity(mapping: ['game_type_id' => 'id'])] GameType $gameType,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(GameTypeVoter::DELETE, $gameType);

        $this->validateCsrf('game_type_delete', $request->request->get('_token'));

        $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
            id: 'Game type has been deleted.',
            domain: 'GameType',
        ));

        $this->manager->delete($gameType);

        return $this->redirectToGameTypesList();
    }

    /** @param DoctrineGameTypeRepository $repository */
    public function deleteBatch(GameTypeRepositoryInterface $repository, Request $request): Response
    {
        $this->denyAccessUnlessGranted(GameTypeVoter::DELETE_BATCH);

        $this->validateCsrf('game_type_delete_batch', $request->request->get('_token'));

        $gameTypeIds = $request->request->all('gameTypes');

        $notAllDeleted = false;
        foreach ($gameTypeIds as $gameTypeId) {
            $gameType = $repository->find($gameTypeId);
            if ($gameType) {
                if (!$this->isGranted(GameTypeVoter::DELETE, $gameType)) {
                    $notAllDeleted = true;

                    continue;
                }

                $this->manager->delete($gameType);
            }
        }

        if ($notAllDeleted) {
            $this->makeFlash(FlashType::WARNING, $this->translator->trans(
                id: 'Not all chosen game types have been deleted.',
                domain: 'GameType',
            ));
        } else {
            $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
                id: 'Game types have been deleted.',
                domain: 'GameType',
            ));
        }

        return $this->redirectToGameTypesList();
    }
}
