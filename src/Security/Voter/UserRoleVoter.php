<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserRoleVoter extends Voter
{
    const LIST         = 'user_role_list';
    const CREATE       = 'user_role_create';
    const EDIT         = 'user_role_edit';
    const DELETE       = 'user_role_delete';
    const DELETE_BATCH = 'user_role_delete_batch';

    protected function supports(string $attribute, $subject): bool
    {
        return \in_array(
            $attribute,
            [
                self::LIST,
                self::CREATE,
                self::EDIT,
                self::DELETE,
                self::DELETE_BATCH,
            ],
            true
        );
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::LIST:         return $this->canList($user);
            case self::CREATE:       return $this->canCreate($user);
            case self::EDIT:         return $this->canEdit($user);
            case self::DELETE:       return $this->canDelete($user);
            case self::DELETE_BATCH: return $this->canDeleteBatch($user);
            default:                 throw new \LogicException();
        }
    }

    private function canList(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    private function canCreate(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    private function canEdit(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    private function canDelete(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    private function canDeleteBatch(User $user): bool
    {
        return $this->canDelete($user);
    }
}
