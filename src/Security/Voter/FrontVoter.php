<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function in_array;

class FrontVoter extends Voter
{
    public const FRONT = 'front_front';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array(
            $attribute,
            [
                self::FRONT,
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
            self::FRONT => $this->canSeeFront(),
            default     => throw new LogicException(),
        };
    }

    private function canSeeFront(): bool
    {
        return true;
    }
}
