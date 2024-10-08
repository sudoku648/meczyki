<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Security\Infrastructure\Voter;

use LogicException;
use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\User\Domain\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function in_array;

class MatchGameVoter extends Voter
{
    public const string LIST         = 'match_game_list';
    public const string CREATE       = 'match_game_create';
    public const string SHOW         = 'match_game_show';
    public const string EDIT         = 'match_game_edit';
    public const string DELETE       = 'match_game_delete';
    public const string DELETE_BATCH = 'match_game_delete_batch';
    public const string CREATE_BILL  = 'match_game_bill_create';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array(
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

        return match ($attribute) {
            self::LIST         => $this->canList(),
            self::CREATE       => $this->canCreate($user),
            self::SHOW         => $this->canSee($user),
            self::EDIT         => $this->canEdit($subject, $user),
            self::DELETE       => $this->canDelete($user),
            self::DELETE_BATCH => $this->canDeleteBatch($user),
            self::CREATE_BILL  => $this->canCreateBill($subject, $user),
            default            => throw new LogicException(),
        };
    }

    private function canList(): bool
    {
        return true;
    }

    private function canCreate(User $user): bool
    {
        if ($user->isPerson()) {
            return true;
        }

        return $user->isSuperAdmin();
    }

    private function canSee(User $user): bool
    {
        if ($user->isPerson()) {
            return true;
        }

        return $user->isSuperAdmin();
    }

    private function canEdit(MatchGame $matchGame, User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
        if (!$user->isPerson()) {
            return false;
        }

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
        if (!$user->isPerson()) {
            return false;
        }

        $person = $user->getPerson();

        return $person->isInMatchGame($matchGame) && !$person->hasBillForMatchGame($matchGame);
    }
}
