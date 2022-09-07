<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Enums\PermissionEnum;
use App\Entity\GameType;
use App\Entity\User;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function in_array;

class GameTypeVoter extends Voter
{
    public const LIST         = 'game_type_list';
    public const CREATE       = 'game_type_create';
    public const SHOW         = 'game_type_show';
    public const EDIT         = 'game_type_edit';
    public const DELETE       = 'game_type_delete';
    public const DELETE_BATCH = 'game_type_delete_batch';
    public const DELETE_IMAGE = 'game_type_delete_image';

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
                self::DELETE_IMAGE,
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
            self::SHOW         => $this->canSee(),
            self::EDIT         => $this->canEdit($user),
            self::DELETE       => $this->canDelete($user),
            self::DELETE_BATCH => $this->canDeleteBatch($user),
            self::DELETE_IMAGE => $this->canDeleteImage($subject, $user),
            default            => throw new LogicException(),
        };
    }

    private function canList(): bool
    {
        return true;
    }

    private function canCreate(User $user): bool
    {
        if ($user->isGranted(PermissionEnum::MANAGE_GAME_TYPES)) {
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
        if ($user->isGranted(PermissionEnum::MANAGE_GAME_TYPES)) {
            return true;
        }

        return $user->isSuperAdmin();
    }

    private function canDelete(User $user): bool
    {
        if ($user->isGranted(PermissionEnum::MANAGE_GAME_TYPES)) {
            return true;
        }

        return $user->isSuperAdmin();
    }

    private function canDeleteImage(GameType $gameType, User $user): bool
    {
        if (!$gameType->getImage()) {
            return false;
        }
        if ($user->isGranted(PermissionEnum::MANAGE_GAME_TYPES)) {
            return true;
        }

        return $user->isSuperAdmin();
    }

    private function canDeleteBatch(User $user): bool
    {
        return $this->canDelete($user);
    }
}
