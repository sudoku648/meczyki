<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Validator\Constraints;

use DateTime;
use Sudoku648\Meczyki\Person\Frontend\Dto\EditPersonalInfoDto;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class PersonDateOfBirthValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof PersonDateOfBirth) {
            throw new UnexpectedTypeException($constraint, PersonDateOfBirth::class);
        }

        if (null === $value) {
            return;
        }

        if (!$value instanceof EditPersonalInfoDto) {
            throw new UnexpectedTypeException($constraint, EditPersonalInfoDto::class);
        }

        if (null === $value->dateOfBirth) {
            return;
        }

        if ($value->dateOfBirth > new DateTime()) {
            $this->context->buildViolation($constraint->dateOfBirthInFuture)
                ->atPath('dateOfBirth')
                ->setTranslationDomain('Person')
                ->addViolation();
        }

        if (null === $value->pesel) {
            return;
        }

        $year      = (int) $value->dateOfBirth->format('Y');
        $yearShort = $value->dateOfBirth->format('y');
        $month     = $value->dateOfBirth->format('m');
        $day       = $value->dateOfBirth->format('d');

        if ($year < 1800 || $year > 2299) {
            $this->context->buildViolation($constraint->peselNotExists)
                ->atPath('pesel')
                ->setTranslationDomain('Person')
                ->addViolation();

            return;
        }

        if ($year >= 1800 && $year <= 1899) {
            $month += 80;
        }
        if ($year >= 2000 && $year <= 2099) {
            $month += 20;
        }
        if ($year >= 2100 && $year <= 2199) {
            $month += 40;
        }
        if ($year >= 2200 && $year <= 2299) {
            $month += 60;
        }

        $month = (string) $month;

        if (
            $yearShort !== substr($value->pesel, 0, 2) ||
            $month !== substr($value->pesel, 2, 2) ||
            $day !== substr($value->pesel, 4, 2)
        ) {
            $this->context->buildViolation($constraint->peselNotMatchWithDateOfBirth)
                ->atPath('pesel')
                ->setTranslationDomain('Person')
                ->addViolation();
        }
    }
}
