<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Domain\Service;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Club\Frontend\Dto\ClubDto;

interface ClubManagerInterface
{
    public function create(ClubDto $dto): Club;

    public function edit(Club $club, ClubDto $dto): Club;

    public function delete(Club $club): void;

    public function detachEmblem(Club $club): void;
}
