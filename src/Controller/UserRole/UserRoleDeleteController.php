<?php

declare(strict_types=1);

namespace App\Controller\UserRole;

use App\Entity\UserRole;
use App\Message\Flash\UserRole\UserRoleDeletedBatchFlashMessage;
use App\Message\Flash\UserRole\UserRoleDeletedFlashMessage;
use App\Message\Flash\UserRole\UserRoleNotAllDeletedBatchFlashMessage;
use App\Repository\UserRoleRepository;
use App\Security\Voter\UserRoleVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserRoleDeleteController extends UserRoleAbstractController
{
    #[ParamConverter('userRole', options: ['mapping' => ['user_role_id' => 'id']])]
    public function delete(UserRole $userRole, Request $request): Response
    {
        $this->denyAccessUnlessGranted(UserRoleVoter::DELETE, $userRole);

        $this->validateCsrf('user_role_delete', $request->request->get('_token'));

        $this->flash(new UserRoleDeletedFlashMessage($userRole->getId()));

        $this->manager->delete($userRole);

        return $this->redirectToUserRolesList();
    }

    public function deleteBatch(UserRoleRepository $repository, Request $request): Response
    {
        $this->denyAccessUnlessGranted(UserRoleVoter::DELETE_BATCH);

        $this->validateCsrf('user_role_delete_batch', $request->request->get('_token'));

        $userRoleIds = $request->request->all('userRoles');

        $notAllDeleted = false;
        foreach ($userRoleIds as $userRoleId) {
            $userRole = $repository->find($userRoleId);
            if ($userRole) {
                if ($this->isGranted(UserRoleVoter::DELETE, $userRole)) {
                    $this->manager->delete($userRole);
                    continue;
                }

                $notAllDeleted = true;
            }
        }

        if ($notAllDeleted) {
            $this->flash(new UserRoleNotAllDeletedBatchFlashMessage(), 'warning');
        } else {
            $this->flash(new UserRoleDeletedBatchFlashMessage());
        }

        return $this->redirectToUserRolesList();
    }
}
