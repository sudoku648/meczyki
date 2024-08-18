<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Frontend\Validator\Constraints;

use Sudoku648\Meczyki\MatchGame\Frontend\Dto\MatchGameDto;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class MatchGameSeasonAndRoundValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof MatchGameSeasonAndRound) {
            throw new UnexpectedTypeException($constraint, MatchGameSeasonAndRound::class);
        }

        if (null === $value) {
            return;
        }

        if (!$value instanceof MatchGameDto) {
            throw new UnexpectedTypeException($constraint, MatchGameDto::class);
        }

        if (null === $value->gameType) {
            return;
        }

        if (!$value->gameType->isOfficial() && $value->season) {
            $this->context
                ->buildViolation($constraint->seasonChosenForUnofficialGameType)
                ->atPath('season')
                ->setTranslationDomain('MatchGame')
                ->addViolation();
        }

        if (!$value->gameType->isOfficial() && $value->round) {
            $this->context
                ->buildViolation($constraint->roundChosenForUnofficialGameType)
                ->atPath('round')
                ->setTranslationDomain('MatchGame')
                ->addViolation();
        }
    }
}
