<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\Dto;

use Sudoku648\Meczyki\User\Domain\ValueObject\UserId;
use Sudoku648\Meczyki\User\Frontend\Validator\Constraints as UserAssert;
use Symfony\Component\Validator\Constraints as Assert;

#[UserAssert\UserUnique]
class UpdateUserDto
{
    public function __construct(
        public UserId $userId,
        #[UserAssert\UsernameRequirements]
        public ?string $username = null,
        #[UserAssert\PlainPasswordRequirements]
        public ?string $plainPassword = null,
        #[Assert\Type('boolean')]
        public ?bool $isActive = null,
    ) {
    }
}
