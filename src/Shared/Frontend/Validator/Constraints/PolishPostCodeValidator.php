<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Frontend\Validator\Constraints;

use Stringable;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

use function is_scalar;
use function preg_match;

class PolishPostCodeValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof PolishPostCode) {
            throw new UnexpectedTypeException($constraint, PolishPostCode::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !$value instanceof Stringable) {
            throw new UnexpectedValueException($value, 'string');
        }

        $value = (string) $value;

        if (null !== $constraint->normalizer) {
            $value = ($constraint->normalizer)($value);
        }

        if ($constraint->match xor preg_match($constraint->pattern, $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(PolishPostCode::REGEX_FAILED_ERROR)
                ->addViolation();
        }
    }
}
