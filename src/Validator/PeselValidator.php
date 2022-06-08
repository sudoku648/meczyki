<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class PeselValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof Pesel) {
            throw new UnexpectedTypeException($constraint, Pesel::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!\is_scalar($value) && !$value instanceof \Stringable) {
            throw new UnexpectedValueException($value, 'string');
        }

        $value = (string) $value;

        $length = \strlen($value);

        if ($length < 11) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Pesel::TOO_SHORT_ERROR)
                ->addViolation();

            return;
        }

        if ($length > 11) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Pesel::TOO_LONG_ERROR)
                ->addViolation();

            return;
        }

        if (!\ctype_digit($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Pesel::INVALID_CHARACTERS_ERROR)
                ->addViolation();

            return;
        }

        $weights = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];
        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += $weights[$i] * $value[$i];
        }

        $checkSum = 10 - $sum % 10;
        $checkSum = ($checkSum === 10) ? 0 : $checkSum;

        if ($checkSum !== (int) $value[10]) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Pesel::CHECKSUM_FAILED_ERROR)
                ->addViolation();
        }

        $year     = \substr($value, 0, 2);
        $month    = \substr($value, 2, 2);
        $monthInt = (int) $month;
        $day      = (int) \substr($value, 4, 2);

        if ($monthInt >= 81 && $monthInt <= 92) {
            $month = $monthInt - 80;
            $year = $year + 1800;
        } elseif ($monthInt >= 61 && $monthInt <= 72) {
            $month = $monthInt - 60;
            $year = $year + 2200;
        } elseif ($monthInt >= 41 && $monthInt <= 52) {
            $month = $monthInt - 40;
            $year = $year + 2100;
        } elseif ($monthInt >= 21 && $monthInt <= 32) {
            $month = $monthInt - 20;
            $year = $year + 2000;
        } elseif ($monthInt >= 1 && $monthInt <= 12) {
            $year = $year + 1900;
        } else {
            $year = null;
        }

        if (
            null === $year ||
            $day > \cal_days_in_month(\CAL_GREGORIAN, (int) $month, $year)

        ) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Pesel::DATE_OF_BIRTH_ERROR)
                ->addViolation();
        }
    }
}
