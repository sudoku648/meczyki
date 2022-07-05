<?php

declare(strict_types=1);

namespace App\Controller\Club;

use App\Entity\Club;
use App\Message\Flash\Club\ClubDeletedBatchFlashMessage;
use App\Message\Flash\Club\ClubDeletedFlashMessage;
use App\Message\Flash\Club\ClubNotAllDeletedBatchFlashMessage;
use App\Repository\ClubRepository;
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

        $this->flash(new ClubDeletedFlashMessage($club->getId()));

        $this->manager->delete($club);

        return $this->redirectToClubsList();
    }

    public function deleteBatch(ClubRepository $repository, Request $request): Response
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
            $this->flash(new ClubNotAllDeletedBatchFlashMessage(), 'warning');
        } else {
            $this->flash(new ClubDeletedBatchFlashMessage());
        }

        return $this->redirectToClubsList();
    }
}
