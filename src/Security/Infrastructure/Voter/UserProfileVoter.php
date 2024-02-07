<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Security\Infrastructure\Voter;

use LogicException;
use Sudoku648\Meczyki\User\Domain\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function in_array;

class UserProfileVoter extends Voter
{
    public const PROFILE_SHOW = 'user_profile_show';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array(
            $attribute,
            [
                self::PROFILE_SHOW,
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
            self::PROFILE_SHOW => $this->canSeeProfile($subject, $user),
            default            => throw new LogicException(),
        };
    }

    private function canSeeProfile(User $userEntity, User $user): bool
    {
        return $userEntity === $user;
    }
}
