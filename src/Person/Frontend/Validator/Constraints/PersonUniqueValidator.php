<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Validator\Constraints;

use Sudoku648\Meczyki\Person\Domain\Persistence\PersonRepositoryInterface;
use Sudoku648\Meczyki\Person\Frontend\Dto\PersonDto;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class PersonUniqueValidator extends ConstraintValidator
{
    public function __construct(
        private readonly PersonRepositoryInterface $repository,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof PersonUnique) {
            throw new UnexpectedTypeException($constraint, PersonUnique::class);
        }

        if (null === $value) {
            return;
        }

        if (!$value instanceof PersonDto) {
            throw new UnexpectedTypeException($constraint, PersonDto::class);
        }

        if (null === $value->mobilePhone) {
            return;
        }

        $existsMobilePhone = $this->repository->existsWithMobilePhoneAndId(
            $value->mobilePhone,
            $value->personId,
        );
        if ($existsMobilePhone) {
            $this->context->buildViolation($constraint->mobilePhoneExists)->atPath('mobilePhone')->addViolation();
        }
    }
}
