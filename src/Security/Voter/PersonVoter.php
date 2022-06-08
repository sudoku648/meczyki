<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PersonVoter extends Voter
{
    const LIST               = 'person_list';
    const CREATE             = 'person_create';
    const SHOW               = 'person_show';
    const EDIT               = 'person_edit';
    const DELETE             = 'person_delete';
    const EDIT_PERSONAL_INFO = 'person_personal_info_edit';

    protected function supports(string $attribute, $subject): bool
    {
        return \in_array(
            $attribute,
            [
                self::LIST,
                self::CREATE,
                self::SHOW,
                self::EDIT,
                self::DELETE,
                self::EDIT_PERSONAL_INFO,
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
            case self::LIST:               return $this->canList($user);
            case self::CREATE:             return $this->canCreate();
            case self::SHOW:               return $this->canSee($user);
            case self::EDIT:               return $this->canEdit();
            case self::DELETE:             return $this->canDelete();
            case self::EDIT_PERSONAL_INFO: return $this->canEditPersonalInfo($user);
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

    private function canSee(User $user): bool
    {
        return $user->isPerson();
    }

    private function canEdit(): bool
    {
        return false;
    }

    private function canDelete(): bool
    {
        return false;
    }

    private function canEditPersonalInfo(User $user): bool
    {
        return $user->isPerson();
    }
}
