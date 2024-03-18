<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Infrastructure\Mapper;

use Sudoku648\Meczyki\Person\Domain\ValueObject\MatchGameFunction;

readonly class MatchGameFunctionMapper
{
    /**
     * @param MatchGameFunction[] $array
     * @return string[]
     */
    public function mapEnumsToValues(array $array): array
    {
        return array_map(fn (MatchGameFunction $function) => $function->value, $array);
    }

    /**
     * @param string[] $array
     * @return MatchGameFunction[]
     */
    public function mapValuesToEnums(array $array): array
    {
        return array_map(fn (string $function) => MatchGameFunction::from($function), $array);
    }
}
