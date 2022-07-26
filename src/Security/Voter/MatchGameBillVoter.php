<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\MatchGameBill;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MatchGameBillVoter extends Voter
{
    const SHOW     = 'match_game_bill_show';
    const EDIT     = 'match_game_bill_edit';
    const DELETE   = 'match_game_bill_delete';
    const DOWNLOAD = 'match_game_bill_download';

    protected function supports(string $attribute, $subject): bool
    {
        return \in_array(
            $attribute,
            [
                self::SHOW,
                self::EDIT,
                self::DELETE,
                self::DOWNLOAD,
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

        if (!$subject instanceof MatchGameBill) {
            return false;
        }

        if ($user->isSuperAdmin()) return true;

        switch ($attribute) {
            case self::SHOW:     return $this->canSee($subject, $user);
            case self::EDIT:     return $this->canEdit($subject, $user);
            case self::DELETE:   return $this->canDelete($subject, $user);
            case self::DOWNLOAD: return $this->canDownload($subject, $user);
            default:             throw new \LogicException();
        }
    }

    private function canSee(MatchGameBill $matchGameBill, User $user): bool
    {
        if (!$user->isPerson()) return false;

        $person = $user->getPerson();

        if ($matchGameBill->getPerson() !== $person) return false;

        return true;
    }

    private function canEdit(MatchGameBill $matchGameBill, User $user): bool
    {
        if (!$user->isPerson()) return false;

        $person = $user->getPerson();

        if ($matchGameBill->getPerson() !== $person) return false;

        return true;
    }

    private function canDelete(MatchGameBill $matchGameBill, User $user): bool
    {
        if (!$user->isPerson()) return false;

        $person = $user->getPerson();

        if ($matchGameBill->getPerson() !== $person) return false;

        return true;
    }

    private function canDownload(MatchGameBill $matchGameBill, User $user): bool
    {
        if (!$user->isPerson()) return false;

        $person = $user->getPerson();

        if ($matchGameBill->getPerson() !== $person) return false;

        return true;
    }
}
