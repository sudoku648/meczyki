<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class RefereeObserverVoter extends Voter
{
    const LIST   = 'referee_observer_list';
    const CREATE = 'referee_observer_create';

    protected function supports(string $attribute, $subject): bool
    {
        return \in_array(
            $attribute,
            [
                self::LIST,
                self::CREATE,
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

        if ($user->isSuperAdmin()) return true;

        switch ($attribute) {
            case self::LIST:   return $this->canList($user);
            case self::CREATE: return $this->canCreate();
            default: throw new \LogicException();
        }
    }

    private function canList(User $user): bool
    {
        return $user->isPerson();
    }

    private function canCreate(): bool
    {
        return false;
    }
}