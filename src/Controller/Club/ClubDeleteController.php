<?php

declare(strict_types=1);

namespace App\Controller\Club;

use App\Entity\Club;
use App\Security\Voter\ClubVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClubDeleteController extends ClubAbstractController
{
    #[ParamConverter('club', options: ['mapping' => ['club_id' => 'id']])]
    public function delete(Club $club, Request $request): Response
    {
        $this->denyAccessUnlessGranted(ClubVoter::DELETE, $club);

        $this->validateCsrf('club_delete', $request->request->get('_token'));

        $this->manager->delete($club);

        return $this->redirectToClubsList();
    }
}
