<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Domain\Service;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Club\Frontend\Dto\CreateClubDto;
use Sudoku648\Meczyki\Club\Frontend\Dto\UpdateClubDto;

interface ClubManagerInterface
{
    public function create(CreateClubDto $dto): Club;

    public function edit(Club $club, UpdateClubDto $dto): Club;

    public function delete(Club $club): void;

    public function detachEmblem(Club $club): void;
}
