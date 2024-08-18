<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Validator\Constraints;

use Sudoku648\Meczyki\Person\Domain\Persistence\PersonRepositoryInterface;
use Sudoku648\Meczyki\Person\Frontend\Dto\CreatePersonDto;
use Sudoku648\Meczyki\Person\Frontend\Dto\UpdatePersonDto;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use function sprintf;

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

        if (!$value instanceof CreatePersonDto && !$value instanceof UpdatePersonDto) {
            throw new UnexpectedTypeException($constraint, sprintf('%s or %s', CreatePersonDto::class, UpdatePersonDto::class));
        }

        if (null === $value->mobilePhone) {
            return;
        }

        $existsMobilePhone = $this->repository->existsWithMobilePhoneAndId(
            $value->mobilePhone,
            $value->personId ?? null,
        );
        if ($existsMobilePhone) {
            $this->context->buildViolation($constraint->mobilePhoneExists)->atPath('mobilePhone')->addViolation();
        }
    }
}
