<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\MatchGameBill;
use App\Entity\User;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function in_array;

/**
 * @todo
 */
class MatchGameBillVoter extends Voter
{
    public const SHOW     = 'match_game_bill_show';
    public const EDIT     = 'match_game_bill_edit';
    public const DELETE   = 'match_game_bill_delete';
    public const DOWNLOAD = 'match_game_bill_download';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array(
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

        if ($user->isSuperAdmin()) {
            return true;
        }

        return match ($attribute) {
            self::SHOW     => $this->canSee($subject, $user),
            self::EDIT     => $this->canEdit($subject, $user),
            self::DELETE   => $this->canDelete($subject, $user),
            self::DOWNLOAD => $this->canDownload($subject, $user),
            default        => throw new LogicException(),
        };
    }

    private function canSee(MatchGameBill $matchGameBill, User $user): bool
    {
        if (!$user->isPerson()) {
            return false;
        }

        $person = $user->getPerson();

        if ($matchGameBill->getPerson() !== $person) {
            return false;
        }

        return true;
    }

    private function canEdit(MatchGameBill $matchGameBill, User $user): bool
    {
        if (!$user->isPerson()) {
            return false;
        }

        $person = $user->getPerson();

        if ($matchGameBill->getPerson() !== $person) {
            return false;
        }

        return true;
    }

    private function canDelete(MatchGameBill $matchGameBill, User $user): bool
    {
        if (!$user->isPerson()) {
            return false;
        }

        $person = $user->getPerson();

        if ($matchGameBill->getPerson() !== $person) {
            return false;
        }

        return true;
    }

    private function canDownload(MatchGameBill $matchGameBill, User $user): bool
    {
        if (!$user->isPerson()) {
            return false;
        }

        $person = $user->getPerson();

        if ($matchGameBill->getPerson() !== $person) {
            return false;
        }

        return true;
    }
}
