<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Frontend\Validator\Constraints;

use Stringable;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

use function ctype_digit;
use function is_scalar;
use function strlen;

class NipValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof Nip) {
            throw new UnexpectedTypeException($constraint, Nip::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !$value instanceof Stringable) {
            throw new UnexpectedValueException($value, 'string');
        }

        $value = (string) $value;

        $length = strlen($value);

        if ($length < 10) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Nip::TOO_SHORT_ERROR)
                ->setTranslationDomain('Person')
                ->addViolation();

            return;
        }

        if ($length > 10) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Nip::TOO_LONG_ERROR)
                ->setTranslationDomain('Person')
                ->addViolation();

            return;
        }

        if (!ctype_digit($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Nip::INVALID_CHARACTERS_ERROR)
                ->setTranslationDomain('Person')
                ->addViolation();

            return;
        }

        $weights = [6, 5, 7, 2, 3, 4, 5, 6, 7];
        $sum     = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += $weights[$i] * $value[$i];
        }

        $checkSum = $sum % 11;

        if ($checkSum !== (int) $value[9] || $checkSum === 10) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(Nip::CHECKSUM_FAILED_ERROR)
                ->setTranslationDomain('Person')
                ->addViolation();
        }
    }
}
