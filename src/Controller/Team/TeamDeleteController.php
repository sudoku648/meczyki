<?php

declare(strict_types=1);

namespace App\Controller\Team;

use App\Entity\Club;
use App\Entity\Team;
use App\Repository\TeamRepository;
use App\Security\Voter\TeamVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamDeleteController extends TeamAbstractController
{
    #[ParamConverter('club', options: ['mapping' => ['club_id' => 'id']])]
    #[ParamConverter('team', options: ['mapping' => ['team_id' => 'id']])]
    public function delete(Club $club, Team $team, Request $request): Response
    {
        $this->denyAccessUnlessGranted(TeamVoter::DELETE, $team);

        $this->validateCsrf('team_delete', $request->request->get('_token'));

        $this->addFlash('success', 'Drużyna została usunięta.');

        $this->manager->delete($team);

        if ($request->request->get('show_club', false)) {
            return $this->redirectToSingleClub($club);
        }

        return $this->redirectToTeamsList();
    }

    public function deleteBatch(TeamRepository $repository, Request $request): Response
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
            $this->addFlash('warning', 'Nie wszystkie drużyny zostały usunięte.');
        } else {
            $this->addFlash('success', 'Drużyny zostały usunięte.');
        }

        return $this->redirectToTeamsList();
    }
}
