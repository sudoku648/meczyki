<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Frontend\Validator\Constraints;

use Sudoku648\Meczyki\GameType\Domain\Persistence\GameTypeRepositoryInterface;
use Sudoku648\Meczyki\GameType\Domain\ValueObject\GameTypeName;
use Sudoku648\Meczyki\GameType\Frontend\Dto\CreateGameTypeDto;
use Sudoku648\Meczyki\GameType\Frontend\Dto\UpdateGameTypeDto;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use function sprintf;

final class GameTypeUniqueValidator extends ConstraintValidator
{
    public function __construct(
        private readonly GameTypeRepositoryInterface $repository,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof GameTypeUnique) {
            throw new UnexpectedTypeException($constraint, GameTypeUnique::class);
        }

        if (null === $value) {
            return;
        }

        if (!$value instanceof CreateGameTypeDto && !$value instanceof UpdateGameTypeDto) {
            throw new UnexpectedTypeException($constraint, sprintf('%s or %s', CreateGameTypeDto::class, UpdateGameTypeDto::class));
        }

        if (null === $value->name) {
            return;
        }

        $existsName = $this->repository->existsWithNameAndId(
            GameTypeName::fromString($value->name),
            $value->gameTypeId ?? null,
        );
        if ($existsName) {
            $this->context->buildViolation($constraint->nameExists)->atPath('name')->addViolation();
        }
    }
}
