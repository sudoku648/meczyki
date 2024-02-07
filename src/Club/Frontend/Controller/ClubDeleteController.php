<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Frontend\Controller;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Club\Domain\Persistence\ClubRepositoryInterface;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\ClubVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClubDeleteController extends ClubAbstractController
{
    public function delete(
        #[MapEntity(mapping: ['club_id' => 'id'])] Club $club,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(ClubVoter::DELETE, $club);

        $this->validateCsrf('club_delete', $request->request->get('_token'));

        $this->addFlash('success', 'Klub został usunięty.');

        $this->manager->delete($club);

        return $this->redirectToClubsList();
    }

    public function deleteBatch(ClubRepositoryInterface $repository, Request $request): Response
    {
        $this->denyAccessUnlessGranted(ClubVoter::DELETE_BATCH);

        $this->validateCsrf('club_delete_batch', $request->request->get('_token'));

        $clubIds = $request->request->all('clubs');

        $notAllDeleted = false;
        foreach ($clubIds as $clubId) {
            $club = $repository->find($clubId);
            if ($club) {
                if ($this->isGranted(ClubVoter::DELETE, $club)) {
                    $this->manager->delete($club);

                    continue;
                }

                $notAllDeleted = true;
            }
        }

        if ($notAllDeleted) {
            $this->addFlash('warning', 'Nie wszystkie kluby zostały usunięte.');
        } else {
            $this->addFlash('success', 'Kluby zostały usunięte.');
        }

        return $this->redirectToClubsList();
    }
}
