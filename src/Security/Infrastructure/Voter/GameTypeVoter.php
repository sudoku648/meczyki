<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Security\Infrastructure\Voter;

use LogicException;
use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;
use Sudoku648\Meczyki\User\Domain\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function in_array;

class GameTypeVoter extends Voter
{
    public const string LIST         = 'game_type_list';
    public const string CREATE       = 'game_type_create';
    public const string SHOW         = 'game_type_show';
    public const string EDIT         = 'game_type_edit';
    public const string DELETE       = 'game_type_delete';
    public const string DELETE_BATCH = 'game_type_delete_batch';
    public const string DELETE_IMAGE = 'game_type_delete_image';

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

    private function canDeleteImage(GameType $gameType, User $user): bool
    {
        if (!$gameType->getImage()) {
            return false;
        }

        return $user->isSuperAdmin();
    }

    private function canDeleteBatch(User $user): bool
    {
        return $this->canDelete($user);
    }
}
