<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Security\Infrastructure\Voter;

use LogicException;
use Sudoku648\Meczyki\User\Domain\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function in_array;

class DashboardVoter extends Voter
{
    public const string DASHBOARD = 'dashboard_dashboard';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array(
            $attribute,
            [
                self::DASHBOARD,
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
            self::DASHBOARD => $this->canSeeDashboard(),
            default         => throw new LogicException(),
        };
    }

    private function canSeeDashboard(): bool
    {
        return true;
    }
}
