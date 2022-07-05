<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Club;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ClubVoter extends Voter
{
    const LIST          = 'club_list';
    const CREATE        = 'club_create';
    const SHOW          = 'club_show';
    const EDIT          = 'club_edit';
    const DELETE        = 'club_delete';
    const DELETE_BATCH  = 'club_delete_batch';
    const DELETE_EMBLEM = 'club_delete_emblem';

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
                self::DELETE_EMBLEM,
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
            case self::LIST:          return $this->canList($user);
            case self::CREATE:        return $this->canCreate($user);
            case self::SHOW:          return $this->canSee($user);
            case self::EDIT:          return $this->canEdit($user);
            case self::DELETE:        return $this->canDelete($user);
            case self::DELETE_BATCH:  return $this->canDeleteBatch($user);
            case self::DELETE_EMBLEM: return $this->canDeleteEmblem($subject, $user);
            default: throw new \LogicException();
        }
    }

    private function canList(User $user): bool
    {
        return $user->isUser();
    }

    private function canCreate(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    private function canSee(User $user): bool
    {
        return $user->isUser();
    }

    private function canEdit(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    private function canDelete(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    private function canDeleteEmblem(Club $club, User $user): bool
    {
        return $club->getEmblem() && $user->isSuperAdmin();
    }

    private function canDeleteBatch(User $user): bool
    {
        return $this->canDelete($user);
    }
}
