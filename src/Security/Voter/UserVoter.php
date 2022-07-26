<?php

declare(strict_types=1);

namespace App\Security\Voter;

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

        if (!$user->isSuperAdmin()) return false;

        switch ($attribute) {
            case self::LIST:             return $this->canList();
            case self::CREATE:           return $this->canCreate();
            case self::SHOW:             return $this->canSee();
            case self::EDIT:             return $this->canEdit();
            case self::ACTIVATE:         return $this->canActivate($subject);
            case self::DEACTIVATE:       return $this->canDeactivate($subject, $user);
            case self::ACTIVATE_BATCH:   return $this->canActivateBatch();
            case self::DEACTIVATE_BATCH: return $this->canDeactivateBatch();
            case self::DELETE:           return $this->canDelete($subject);
            case self::DELETE_BATCH:     return $this->canDeleteBatch();
            case self::IMPERSONATE:      return $this->canImpersonate($subject, $user);
            case self::BIND_WITH_PERSON: return $this->canBindWithPerson();
            default:                     throw new \LogicException();
        }
    }

    private function canList(): bool
    {
        return true;
    }

    private function canCreate(): bool
    {
        return true;
    }

    private function canSee(): bool
    {
        return true;
    }

    private function canEdit(): bool
    {
        return true;
    }

    private function canActivate(User $userEntity): bool
    {
        return !$userEntity->isActive();
    }

    private function canDeactivate(User $userEntity, User $user): bool
    {
        return $userEntity->isActive() && $userEntity !== $user;
    }

    private function canActivateBatch(): bool
    {
        return true;
    }

    private function canDeactivateBatch(): bool
    {
        return true;
    }

    private function canDelete(User $userEntity): bool
    {
        return !$userEntity->isActive();
    }

    private function canDeleteBatch(): bool
    {
        return true;
    }

    private function canImpersonate(User $userEntity, User $user): bool
    {
        return $userEntity->isActive() && $userEntity !== $user;
    }

    private function canBindWithPerson(): bool
    {
        return true;
    }
}
