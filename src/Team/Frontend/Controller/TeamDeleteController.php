<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Frontend\Controller;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\TeamVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Enums\FlashType;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Traits\RedirectTrait;
use Sudoku648\Meczyki\Team\Domain\Entity\Team;
use Sudoku648\Meczyki\Team\Domain\Persistence\TeamRepositoryInterface;
use Sudoku648\Meczyki\Team\Domain\Service\TeamManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class TeamDeleteController extends AbstractController
{
    use RedirectTrait;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly TeamManagerInterface $manager,
    ) {
    }

    public function delete(
        #[MapEntity(mapping: ['club_id' => 'id'])] Club $club,
        #[MapEntity(mapping: ['team_id' => 'id'])] Team $team,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(TeamVoter::DELETE, $team);

        $this->validateCsrf('team_delete', $request->request->get('_token'));

        $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
            id: 'Team has been deleted.',
            domain: 'Team',
        ));

        $this->manager->delete($team);

        if ($request->request->get('show_club', false)) {
            return $this->redirectToSingleClub($club);
        }

        return $this->redirectToTeamsList();
    }

    public function deleteBatch(TeamRepositoryInterface $repository, Request $request): Response
    {
        $this->denyAccessUnlessGranted(TeamVoter::DELETE_BATCH);

        $this->validateCsrf('team_delete_batch', $request->request->get('_token'));

        $teamIds = $request->request->all('teams');

        $notAllDeleted = false;
        foreach ($teamIds as $teamId) {
            $team = $repository->find($teamId);
            if ($team) {
                if ($this->isGranted(TeamVoter::DELETE, $team)) {
                    $this->manager->delete($team);

                    continue;
                }

                $notAllDeleted = true;
            }
        }

        if ($notAllDeleted) {
            $this->makeFlash(FlashType::WARNING, $this->translator->trans(
                id: 'Not all chosen teams have been deleted.',
                domain: 'Team',
            ));
        } else {
            $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
                id: 'Teams have been deleted.',
                domain: 'Team',
            ));
        }

        return $this->redirectToTeamsList();
    }
}
