<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Club;
use App\Entity\Enums\PermissionEnum;
use App\Entity\User;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function in_array;

class ClubVoter extends Voter
{
    public const LIST          = 'club_list';
    public const CREATE        = 'club_create';
    public const SHOW          = 'club_show';
    public const EDIT          = 'club_edit';
    public const DELETE        = 'club_delete';
    public const DELETE_BATCH  = 'club_delete_batch';
    public const DELETE_EMBLEM = 'club_delete_emblem';

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
        if ($user->isGranted(PermissionEnum::MANAGE_CLUBS)) {
            return true;
        }

        return $user->isSuperAdmin();
    }

    private function canSee(): bool
    {
        return true;
    }

    private function canEdit(User $user): bool
    {
        if ($user->isGranted(PermissionEnum::MANAGE_CLUBS)) {
            return true;
        }

        return $user->isSuperAdmin();
    }

    private function canDelete(User $user): bool
    {
        if ($user->isGranted(PermissionEnum::MANAGE_CLUBS)) {
            return true;
        }

        return $user->isSuperAdmin();
    }

    private function canDeleteEmblem(Club $club, User $user): bool
    {
        if (!$club->getEmblem()) {
            return false;
        }
        if ($user->isGranted(PermissionEnum::MANAGE_CLUBS)) {
            return true;
        }

        return $user->isSuperAdmin();
    }

    private function canDeleteBatch(User $user): bool
    {
        return $this->canDelete($user);
    }
}
