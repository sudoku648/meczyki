<?php

declare(strict_types=1);

namespace App\Controller\UserRole;

use App\Entity\UserRole;
use App\Repository\UserRoleRepository;
use App\Security\Voter\UserRoleVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserRoleDeleteController extends UserRoleAbstractController
{
    public function delete(
        #[MapEntity(mapping: ['user_role_id' => 'id'])] UserRole $userRole,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(UserRoleVoter::DELETE, $userRole);

        $this->validateCsrf('user_role_delete', $request->request->get('_token'));

        $this->addFlash('success', 'Rola użytkowników została usunięta.');

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
            $this->addFlash('warning', 'Nie wszystkie role użytkowników zostały usunięte.');
        } else {
            $this->addFlash('success', 'Role użytkowników zostały usunięte.');
        }

        return $this->redirectToUserRolesList();
    }
}