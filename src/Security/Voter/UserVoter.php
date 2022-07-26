<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Enums\PermissionEnum;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    const LIST             = 'user_list';
    const CREATE           = 'user_create';
    const SHOW             = 'user_show';
    const EDIT             = 'user_edit';
    const ACTIVATE         = 'user_activate';
    const DEACTIVATE       = 'user_deactivate';
    const ACTIVATE_BATCH   = 'user_activate_batch';
    const DEACTIVATE_BATCH = 'user_deactivate_batch';
    const DELETE           = 'user_delete';
    const DELETE_BATCH     = 'user_delete_batch';
    const IMPERSONATE      = 'user_impersonate';
    const BIND_WITH_PERSON = 'user_bind_with_person';

    protected function supports(string $attribute, $subject): bool
    {
        return \in_array(
            $attribute,
            [
                self::LIST,
                self::CREATE,
                self::SHOW,
                self::EDIT,
                self::ACTIVATE,
                self::DEACTIVATE,
                self::ACTIVATE_BATCH,
                self::DEACTIVATE_BATCH,
                self::DELETE,
                self::DELETE_BATCH,
                self::IMPERSONATE,
                self::BIND_WITH_PERSON,
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
            case self::LIST:             return $this->canList($user);
            case self::CREATE:           return $this->canCreate($user);
            case self::SHOW:             return $this->canSee($user);
            case self::EDIT:             return $this->canEdit($user);
            case self::ACTIVATE:         return $this->canActivate($subject, $user);
            case self::DEACTIVATE:       return $this->canDeactivate($subject, $user);
            case self::ACTIVATE_BATCH:   return $this->canActivateBatch($user);
            case self::DEACTIVATE_BATCH: return $this->canDeactivateBatch($user);
            case self::DELETE:           return $this->canDelete($subject, $user);
            case self::DELETE_BATCH:     return $this->canDeleteBatch($user);
            case self::IMPERSONATE:      return $this->canImpersonate($subject, $user);
            case self::BIND_WITH_PERSON: return $this->canBindWithPerson($user);
            default:                     throw new \LogicException();
        }
    }

    private function canList(User $user): bool
    {
        if ($user->isGranted(PermissionEnum::MANAGE_USERS)) {
            return true;
        }

        return $user->isSuperAdmin();
    }

    private function canCreate(User $user): bool
    {
        if ($user->isGranted(PermissionEnum::MANAGE_USERS)) {
            return true;
        }

        return $user->isSuperAdmin();
    }

    private function canSee(User $user): bool
    {
        if ($user->isGranted(PermissionEnum::MANAGE_USERS)) {
            return true;
        }

        return $user->isSuperAdmin();
    }

    private function canEdit(User $user): bool
    {
        if ($user->isGranted(PermissionEnum::MANAGE_USERS)) {
            return true;
        }

        return $user->isSuperAdmin();
    }

    private function canActivate(User $userEntity, User $user): bool
    {
        if ($userEntity->isActive()) {
            return false;
        }
        if ($user->isGranted(PermissionEnum::MANAGE_USERS)) {
            return true;
        }

        return $user->isSuperAdmin();
    }

    private function canDeactivate(User $userEntity, User $user): bool
    {
        if (!$userEntity->isActive()) {
            return false;
        }
        if ($userEntity === $user) {
            return false;
        }
        if ($user->isGranted(PermissionEnum::MANAGE_USERS)) {
            return true;
        }

        return $user->isSuperAdmin();
    }

    private function canActivateBatch(User $user): bool
    {
        if ($user->isGranted(PermissionEnum::MANAGE_USERS)) {
            return true;
        }

        return $user->isSuperAdmin();
    }

    private function canDeactivateBatch(User $user): bool
    {
        if ($user->isGranted(PermissionEnum::MANAGE_USERS)) {
            return true;
        }

        return $user->isSuperAdmin();
    }

    private function canDelete(User $userEntity, User $user): bool
    {
        if ($userEntity->isActive()) {
            return false;
        }
        if ($user->isGranted(PermissionEnum::MANAGE_USERS)) {
            return true;
        }

        return $user->isSuperAdmin();
    }

    private function canDeleteBatch(User $user): bool
    {
        if ($user->isGranted(PermissionEnum::MANAGE_USERS)) {
            return true;
        }

        return $user->isSuperAdmin();
    }

    private function canImpersonate(User $userEntity, User $user): bool
    {
        if (!$user->isSuperAdmin()) {
            return false;
        }

        return $userEntity->isActive() && $userEntity !== $user;
    }

    private function canBindWithPerson(User $user): bool
    {
        if ($user->isGranted(PermissionEnum::MANAGE_USERS)) {
            return true;
        }

        return $user->isSuperAdmin();
    }
}
