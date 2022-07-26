<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\GameType;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class GameTypeVoter extends Voter
{
    const LIST         = 'game_type_list';
    const CREATE       = 'game_type_create';
    const SHOW         = 'game_type_show';
    const EDIT         = 'game_type_edit';
    const DELETE       = 'game_type_delete';
    const DELETE_BATCH = 'game_type_delete_batch';
    const DELETE_IMAGE = 'game_type_delete_image';

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
                self::DELETE_IMAGE,
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
            case self::LIST:         return $this->canList();
            case self::CREATE:       return $this->canCreate($user);
            case self::SHOW:         return $this->canSee();
            case self::EDIT:         return $this->canEdit($user);
            case self::DELETE:       return $this->canDelete($user);
            case self::DELETE_BATCH: return $this->canDeleteBatch($user);
            case self::DELETE_IMAGE: return $this->canDeleteImage($subject, $user);
            default:                 throw new \LogicException();
        }
    }

    private function canList(): bool
    {
        return true;
    }

    private function canCreate(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    private function canSee(): bool
    {
        return true;
    }

    private function canEdit(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    private function canDelete(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    private function canDeleteImage(GameType $gameType, User $user): bool
    {
        return $gameType->getImage() && $user->isSuperAdmin();
    }

    private function canDeleteBatch(User $user): bool
    {
        return $this->canDelete($user);
    }
}
