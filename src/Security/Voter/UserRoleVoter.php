<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function in_array;

class UserRoleVoter extends Voter
{
    public const LIST         = 'user_role_list';
    public const CREATE       = 'user_role_create';
    public const EDIT         = 'user_role_edit';
    public const DELETE       = 'user_role_delete';
    public const DELETE_BATCH = 'user_role_delete_batch';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array(
            $attribute,
            [
                self::LIST,
                self::CREATE,
                self::EDIT,
                self::DELETE,
                self::DELETE_BATCH,
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
            self::LIST         => $this->canList($user),
            self::CREATE       => $this->canCreate($user),
            self::EDIT         => $this->canEdit($user),
            self::DELETE       => $this->canDelete($user),
            self::DELETE_BATCH => $this->canDeleteBatch($user),
            default            => throw new LogicException(),
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

    private function canEdit(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    private function canDelete(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    private function canDeleteBatch(User $user): bool
    {
        return $this->canDelete($user);
    }
}
