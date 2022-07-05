<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\MatchGame;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MatchGameVoter extends Voter
{
    const LIST         = 'match_game_list';
    const CREATE       = 'match_game_create';
    const SHOW         = 'match_game_show';
    const EDIT         = 'match_game_edit';
    const DELETE       = 'match_game_delete';
    const DELETE_BATCH = 'match_game_delete_batch';
    const CREATE_BILL  = 'match_game_bill_create';

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
                self::CREATE_BILL,
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
            case self::SHOW:         return $this->canSee($user);
            case self::EDIT:         return $this->canEdit($subject, $user);
            case self::DELETE:       return $this->canDelete($user);
            case self::DELETE_BATCH: return $this->canDeleteBatch($user);
            case self::CREATE_BILL:  return $this->canCreateBill($subject, $user);
            default: throw new \LogicException();
        }
    }

    private function canList(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isPerson();
    }

    private function canCreate(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isPerson();
    }

    private function canSee(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    private function canEdit(MatchGame $matchGame, User $user): bool
    {
        if ($user->isSuperAdmin()) return true;

        if (!$user->isPerson()) return false;

        $person = $user->getPerson();

        return $person->isInMatchGame($matchGame);
    }

    private function canDelete(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    private function canDeleteBatch(User $user): bool
    {
        return $this->canDelete($user);
    }

    private function canCreateBill(MatchGame $matchGame, User $user): bool
    {
        if (!$user->isPerson()) return false;

        $person = $user->getPerson();

        return $person->isInMatchGame($matchGame) && !$person->hasBillForMatchGame($matchGame);
    }
}
