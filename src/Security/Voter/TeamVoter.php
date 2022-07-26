<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Enums\PermissionEnum;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class TeamVoter extends Voter
{
    const LIST         = 'team_list';
    const CREATE       = 'team_create';
    const SHOW         = 'team_show';
    const EDIT         = 'team_edit';
    const DELETE       = 'team_delete';
    const DELETE_BATCH = 'team_delete_batch';

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

        if ($user->isSuperAdmin()) return true;

        switch ($attribute) {
            case self::LIST:         return $this->canList();
            case self::CREATE:       return $this->canCreate($user);
            case self::SHOW:         return $this->canSee();
            case self::EDIT:         return $this->canEdit($user);
            case self::DELETE:       return $this->canDelete($user);
            case self::DELETE_BATCH: return $this->canDeleteBatch($user);
            default:                 throw new \LogicException();
        }
    }

    private function canList(): bool
    {
        return true;
    }

    private function canCreate(User $user): bool
    {
        if ($user->isGranted(PermissionEnum::MANAGE_CLUBS)) {
            return true;
        }

        return false;
    }

    private function canSee(): bool
    {
        return true;
    }

    private function canEdit(User $user): bool
    {
        if ($user->isGranted(PermissionEnum::MANAGE_CLUBS)) {
            return true;
        }

        return false;
    }

    private function canDelete(User $user): bool
    {
        if ($user->isGranted(PermissionEnum::MANAGE_CLUBS)) {
            return true;
        }

        return false;
    }

    private function canDeleteBatch(User $user): bool
    {
        return $this->canDelete($user);
    }
}
