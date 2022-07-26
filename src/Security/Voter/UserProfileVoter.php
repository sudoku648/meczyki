<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserProfileVoter extends Voter
{
    const PROFILE_SHOW = 'user_profile_show';

    protected function supports(string $attribute, $subject): bool
    {
        return \in_array(
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

        switch ($attribute) {
            case self::PROFILE_SHOW: return $this->canSeeProfile($subject, $user);
            default:                 throw new \LogicException();
        }
    }

    private function canSeeProfile(User $userEntity, User $user): bool
    {
        return $userEntity === $user;
    }
}
