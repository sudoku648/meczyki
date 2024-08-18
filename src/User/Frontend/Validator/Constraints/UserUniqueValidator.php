<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\Validator\Constraints;

use Sudoku648\Meczyki\User\Domain\Persistence\UserRepositoryInterface;
use Sudoku648\Meczyki\User\Domain\ValueObject\Username;
use Sudoku648\Meczyki\User\Frontend\Dto\CreateUserDto;
use Sudoku648\Meczyki\User\Frontend\Dto\UpdateUserDto;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use function sprintf;

final class UserUniqueValidator extends ConstraintValidator
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UserUnique) {
            throw new UnexpectedTypeException($constraint, UserUnique::class);
        }

        if (null === $value) {
            return;
        }

        if (!$value instanceof CreateUserDto && !$value instanceof UpdateUserDto) {
            throw new UnexpectedTypeException($constraint, sprintf('%s or %s', CreateUserDto::class, UpdateUserDto::class));
        }

        if (null === $value->username) {
            return;
        }

        $existsUsername = $this->repository->existsWithUsernameAndId(
            Username::fromString($value->username),
            $value->userId ?? null,
        );
        if ($existsUsername) {
            $this->context->buildViolation($constraint->usernameExists)->atPath('username')->addViolation();
        }
    }
}
