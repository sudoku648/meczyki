<?php

declare(strict_types=1);

namespace App\Dto;

class RefereeObserverDto extends PersonDto
{
    #[Assert\Type('boolean')]
    public ?bool $isRefereeObserver = true;
}
