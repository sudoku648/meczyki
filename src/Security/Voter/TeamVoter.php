<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function in_array;

class TeamVoter extends Voter
{
    public const LIST         = 'team_list';
    public const CREATE       = 'team_create';
    public const SHOW         = 'team_show';
    public const EDIT         = 'team_edit';
    public const DELETE       = 'team_delete';
    public const DELETE_BATCH = 'team_delete_batch';

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

        if ($user->isSuperAdmin()) {
            return true;
        }

        return match ($attribute) {
            self::LIST         => $this->canList(),
            self::CREATE       => $this->canCreate($user),
            self::SHOW         => $this->canSee(),
            self::EDIT         => $this->canEdit($user),
            self::DELETE       => $this->canDelete($user),
            self::DELETE_BATCH => $this->canDeleteBatch($user),
            default            => throw new LogicException(),
        };
    }

    private function canList(): bool
    {
        return true;
    }

    private function canCreate(User $user): bool
    {
        return false;
    }

    private function canSee(): bool
    {
        return true;
    }

    private function canEdit(User $user): bool
    {
        return false;
    }

    private function canDelete(User $user): bool
    {
        return false;
    }

    private function canDeleteBatch(User $user): bool
    {
        return $this->canDelete($user);
    }
}
