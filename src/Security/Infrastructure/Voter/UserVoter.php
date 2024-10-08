<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Security\Infrastructure\Voter;

use LogicException;
use Sudoku648\Meczyki\User\Domain\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function in_array;

class UserVoter extends Voter
{
    public const string LIST             = 'user_list';
    public const string CREATE           = 'user_create';
    public const string SHOW             = 'user_show';
    public const string EDIT             = 'user_edit';
    public const string ACTIVATE         = 'user_activate';
    public const string DEACTIVATE       = 'user_deactivate';
    public const string ACTIVATE_BATCH   = 'user_activate_batch';
    public const string DEACTIVATE_BATCH = 'user_deactivate_batch';
    public const string DELETE           = 'user_delete';
    public const string DELETE_BATCH     = 'user_delete_batch';
    public const string IMPERSONATE      = 'user_impersonate';
    public const string BIND_WITH_PERSON = 'user_bind_with_person';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array(
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

        return match ($attribute) {
            self::LIST             => $this->canList($user),
            self::CREATE           => $this->canCreate($user),
            self::SHOW             => $this->canSee($user),
            self::EDIT             => $this->canEdit($user),
            self::ACTIVATE         => $this->canActivate($subject, $user),
            self::DEACTIVATE       => $this->canDeactivate($subject, $user),
            self::ACTIVATE_BATCH   => $this->canActivateBatch($user),
            self::DEACTIVATE_BATCH => $this->canDeactivateBatch($user),
            self::DELETE           => $this->canDelete($subject, $user),
            self::DELETE_BATCH     => $this->canDeleteBatch($user),
            self::IMPERSONATE      => $this->canImpersonate($subject, $user),
            self::BIND_WITH_PERSON => $this->canBindWithPerson($user),
            default                => throw new LogicException(),
        };
    }

    private function canList(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    private function canCreate(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    private function canSee(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    private function canEdit(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    private function canActivate(User $userEntity, User $user): bool
    {
        if ($userEntity->isActive()) {
            return false;
        }

        return $user->isSuperAdmin();
    }

    private function canDeactivate(User $userEntity, User $user): bool
    {
        if (!$userEntity->isActive()) {
            return false;
        }
        if ($userEntity === $user) {
            return false;
        }

        return $user->isSuperAdmin();
    }

    private function canActivateBatch(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    private function canDeactivateBatch(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    private function canDelete(User $userEntity, User $user): bool
    {
        if ($userEntity->isActive()) {
            return false;
        }

        return $user->isSuperAdmin();
    }

    private function canDeleteBatch(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    private function canImpersonate(User $userEntity, User $user): bool
    {
        if (!$user->isSuperAdmin()) {
            return false;
        }

        return $userEntity->isActive() && $userEntity !== $user;
    }

    private function canBindWithPerson(User $user): bool
    {
        return $user->isSuperAdmin();
    }
}
