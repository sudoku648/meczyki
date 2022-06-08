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
    }
}
