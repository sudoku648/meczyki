<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Frontend\Controller;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Club\Domain\Persistence\ClubRepositoryInterface;
use Sudoku648\Meczyki\Club\Domain\Service\ClubManagerInterface;
use Sudoku648\Meczyki\Club\Infrastructure\Persistence\Doctrine\DoctrineClubRepository;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\ClubVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Enums\FlashType;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Traits\RedirectTrait;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ClubDeleteController extends AbstractController
{
    use RedirectTrait;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly ClubManagerInterface $manager,
    ) {
    }

    public function delete(
        #[MapEntity(mapping: ['club_id' => 'id'])] Club $club,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(ClubVoter::DELETE, $club);

        $this->validateCsrf('club_delete', $request->request->get('_token'));

        $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
            id: 'Club has been deleted.',
            domain: 'Club',
        ));

        $this->manager->delete($club);

        return $this->redirectToClubsList();
    }

    /** @param DoctrineClubRepository $repository */
    public function deleteBatch(ClubRepositoryInterface $repository, Request $request): Response
    {
        $this->denyAccessUnlessGranted(ClubVoter::DELETE_BATCH);

        $this->validateCsrf('club_delete_batch', $request->request->get('_token'));

        $clubIds = $request->request->all('clubs');

        $notAllDeleted = false;
        foreach ($clubIds as $clubId) {
            $club = $repository->find($clubId);
            if ($club) {
                if (!$this->isGranted(ClubVoter::DELETE, $club)) {
                    $notAllDeleted = true;

                    continue;
                }

                $this->manager->delete($club);
            }
        }

        if ($notAllDeleted) {
            $this->makeFlash(FlashType::WARNING, $this->translator->trans(
                id: 'Not all chosen clubs have been deleted.',
                domain: 'Club',
            ));
        } else {
            $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
                id: 'Clubs have been deleted.',
                domain: 'Club',
            ));
        }

        return $this->redirectToClubsList();
    }
}
