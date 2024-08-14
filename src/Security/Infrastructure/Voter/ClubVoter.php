<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Security\Infrastructure\Voter;

use LogicException;
use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\User\Domain\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function in_array;

class ClubVoter extends Voter
{
    public const string LIST          = 'club_list';
    public const string CREATE        = 'club_create';
    public const string SHOW          = 'club_show';
    public const string EDIT          = 'club_edit';
    public const string DELETE        = 'club_delete';
    public const string DELETE_BATCH  = 'club_delete_batch';
    public const string DELETE_EMBLEM = 'club_delete_emblem';

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

        return match ($attribute) {
            self::LIST          => $this->canList(),
            self::CREATE        => $this->canCreate($user),
            self::SHOW          => $this->canSee(),
            self::EDIT          => $this->canEdit($user),
            self::DELETE        => $this->canDelete($user),
            self::DELETE_BATCH  => $this->canDeleteBatch($user),
            self::DELETE_EMBLEM => $this->canDeleteEmblem($subject, $user),
            default             => throw new LogicException(),
        };
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

    private function canDeleteEmblem(Club $club, User $user): bool
    {
        if (!$club->getEmblem()) {
            return false;
        }

        return $user->isSuperAdmin();
    }

    private function canDeleteBatch(User $user): bool
    {
        return $this->canDelete($user);
    }
}
