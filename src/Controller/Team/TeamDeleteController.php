<?php

declare(strict_types=1);

namespace App\Controller\Team;

use App\Entity\Club;
use App\Entity\Team;
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

        $this->manager->delete($team);

        return $this->redirectToRefererOrHome($request);
    }
}