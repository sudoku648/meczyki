<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Frontend\Validator\Constraints;

use InvalidArgumentException;
use Sudoku648\Meczyki\MatchGame\Frontend\Dto\MatchGameDto;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use function count;
use function explode;

final class MatchGameSeasonAndDateValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof MatchGameSeasonAndDate) {
            throw new UnexpectedTypeException($constraint, MatchGameSeasonAndDate::class);
        }

        if (null === $value) {
            return;
        }

        if (!$value instanceof MatchGameDto) {
            throw new UnexpectedTypeException($constraint, MatchGameDto::class);
        }

        if (null === $value->season || null === $value->dateTime) {
            return;
        }

        $boundaries = $this->dateBoundaries($value->season);

        if (
            $value->dateTime->format('Y-m-d') < $boundaries[0] ||
            $value->dateTime->format('Y-m-d') > $boundaries[1]
        ) {
            $this->context
                ->buildViolation($constraint->seasonNotMatchDate)
                ->atPath('season')
                ->setTranslationDomain('MatchGame')
                ->addViolation();
        }
    }

    /**
     * @param string $season
     * @return array<string>
     */
    private function dateBoundaries(string $season): array
    {
        $years = $this->parseSeasonString($season);

        return [
            "$years[0]-07-15",
            "$years[1]-07-05",
        ];
    }

    /**
     * @param string $season
     * @return array<string>
     */
    private function parseSeasonString(string $season): array
    {
        $years = explode('/', $season);
        if (count($years) !== 2) {
            throw new InvalidArgumentException();
        }

        return $years;
    }
}
