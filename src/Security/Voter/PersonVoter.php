<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PersonVoter extends Voter
{
    const LIST               = 'person_list';
    const CREATE             = 'person_create';
    const SHOW               = 'person_show';
    const EDIT               = 'person_edit';
    const DELETE             = 'person_delete';
    const DELETE_BATCH       = 'person_delete_batch';
    const EDIT_PERSONAL_INFO = 'person_personal_info_edit';

    protected function supports(string $attribute, $subject): bool
    {
        return \in_array(
            $attribute,
            [
                self::LIST,
                self::CREATE,
                self::SHOW,
                self::EDIT,
                self::DELETE,
                self::DELETE_BATCH,
                self::EDIT_PERSONAL_INFO,
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
            case self::LIST:               return $this->canList($user);
            case self::CREATE:             return $this->canCreate($user);
            case self::SHOW:               return $this->canSee($user);
            case self::EDIT:               return $this->canEdit($user);
            case self::DELETE:             return $this->canDelete($user);
            case self::DELETE_BATCH:       return $this->canDeleteBatch($user);
            case self::EDIT_PERSONAL_INFO: return $this->canEditPersonalInfo($user);
            default:                       throw new \LogicException();
        }
    }

    private function canList(User $user): bool
    {
        if ($user->isSuperAdmin()) return true;

        return $user->isPerson();
    }

    private function canCreate(User $user): bool
    {
        if ($user->isSuperAdmin()) return true;

        return false;
    }

    private function canSee(User $user): bool
    {
        if ($user->isSuperAdmin()) return true;

        return $user->isPerson();
    }

    private function canEdit(User $user): bool
    {
        if ($user->isSuperAdmin()) return true;

        return false;
    }

    private function canDelete(User $user): bool
    {
        if ($user->isSuperAdmin()) return true;

        return false;
    }

    private function canDeleteBatch(User $user): bool
    {
        return $this->canDelete($user);
    }

    private function canEditPersonalInfo(User $user): bool
    {
        return $user->isPerson();
    }
}
