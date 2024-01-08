<?php

declare(strict_types=1);

namespace App\Service\Contracts;

use App\Dto\ClubDto;
use App\Entity\Club;

interface ClubManagerInterface
{
    public function create(ClubDto $dto): Club;

    public function edit(Club $club, ClubDto $dto): Club;

    public function delete(Club $club): void;

    public function detachEmblem(Club $club): void;

    public function createDto(Club $club): ClubDto;
}
