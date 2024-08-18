<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Frontend\Validator\Constraints;

use Sudoku648\Meczyki\MatchGame\Frontend\Dto\MatchGameDto;
use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use function array_diff_assoc;
use function array_intersect;
use function array_keys;
use function array_unique;
use function get_object_vars;

final class MatchGameDuplicatePeopleValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof MatchGameDuplicatePeople) {
            throw new UnexpectedTypeException($constraint, MatchGameDuplicatePeople::class);
        }

        if (null === $value) {
            return;
        }

        if (!$value instanceof MatchGameDto) {
            throw new UnexpectedTypeException($constraint, MatchGameDto::class);
        }

        $allPeopleIds = [];
        foreach (get_object_vars($value) as $prop => $propValue) {
            if (!$propValue instanceof Person) {
                continue;
            }

            $allPeopleIds[$prop] = $propValue->getId();
        }

        $duplicates = array_keys(
            array_intersect(
                $allPeopleIds,
                array_diff_assoc(
                    $allPeopleIds,
                    array_unique($allPeopleIds),
                ),
            ),
        );

        foreach ($duplicates as $path) {
            $this->context->buildViolation($constraint->duplicatedPerson)
                ->atPath($path)
                ->setTranslationDomain('MatchGame')
                ->addViolation();
        }
    }
}
