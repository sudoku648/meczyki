<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Security\Infrastructure\Voter;

use LogicException;
use Sudoku648\Meczyki\User\Domain\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function in_array;

class PersonVoter extends Voter
{
    public const LIST               = 'person_list';
    public const CREATE             = 'person_create';
    public const SHOW               = 'person_show';
    public const EDIT               = 'person_edit';
    public const DELETE             = 'person_delete';
    public const DELETE_BATCH       = 'person_delete_batch';
    public const EDIT_PERSONAL_INFO = 'person_personal_info_edit';

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
                self::EDIT_PERSONAL_INFO,
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
            self::LIST               => $this->canList($user),
            self::CREATE             => $this->canCreate($user),
            self::SHOW               => $this->canSee($user),
            self::EDIT               => $this->canEdit($user),
            self::DELETE             => $this->canDelete($user),
            self::DELETE_BATCH       => $this->canDeleteBatch($user),
            self::EDIT_PERSONAL_INFO => $this->canEditPersonalInfo($user),
            default                  => throw new LogicException(),
        };
    }

    private function canList(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isPerson();
    }

    private function canCreate(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return false;
    }

    private function canSee(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isPerson();
    }

    private function canEdit(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return false;
    }

    private function canDelete(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return false;
    }

    private function canDeleteBatch(User $user): bool
    {
        return $this->canDelete($user);
    }

    private function canEditPersonalInfo(User $user): bool
    {
        return $user->isPerson();
    }
}
