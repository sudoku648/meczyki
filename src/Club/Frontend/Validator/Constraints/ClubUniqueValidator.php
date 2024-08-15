<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Frontend\Validator\Constraints;

use Sudoku648\Meczyki\Club\Domain\Persistence\ClubRepositoryInterface;
use Sudoku648\Meczyki\Club\Domain\ValueObject\ClubName;
use Sudoku648\Meczyki\Club\Frontend\Dto\ClubDto;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class ClubUniqueValidator extends ConstraintValidator
{
    public function __construct(
        private readonly ClubRepositoryInterface $repository,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ClubUnique) {
            throw new UnexpectedTypeException($constraint, ClubUnique::class);
        }

        if (null === $value) {
            return;
        }

        if (!$value instanceof ClubDto) {
            throw new UnexpectedTypeException($constraint, ClubDto::class);
        }

        if (null === $value->name) {
            return;
        }

        $existsName = $this->repository->existsWithNameAndId(
            ClubName::fromString($value->name),
            $value->getId(),
        );
        if ($existsName) {
            $this->context->buildViolation($constraint->nameExists)->atPath('name')->addViolation();
        }
    }
}
