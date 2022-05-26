<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MatchGameVoter extends Voter
{
    const LIST   = 'match_game_list';
    const CREATE = 'match_game_create';
    const SHOW   = 'match_game_show';
    const EDIT   = 'match_game_edit';
    const DELETE = 'match_game_delete';

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
            case self::LIST:   return $this->canList();
            case self::CREATE: return $this->canCreate($user);
            case self::SHOW:   return $this->canSee();
            case self::EDIT:   return $this->canEdit();
            case self::DELETE: return $this->canDelete();
            default: throw new \LogicException();
        }
    }

    private function canList(): bool
    {
        return false;
    }

    private function canCreate(User $user): bool
    {
        return $user->isPerson();
    }

    private function canSee(): bool
    {
        return false;
    }

    // @todo can own matches
    private function canEdit(): bool
    {
        return false;
    }

    private function canDelete(): bool
    {
        return false;
    }
}
