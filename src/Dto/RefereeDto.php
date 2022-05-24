<?php

declare(strict_types=1);

namespace App\Dto;

class RefereeDto extends PersonDto
{
    #[Assert\Type('boolean')]
    public ?bool $isReferee = true;
}
