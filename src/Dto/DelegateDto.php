<?php

declare(strict_types=1);

namespace App\Dto;

class DelegateDto extends PersonDto
{
    #[Assert\Type('boolean')]
    public ?bool $isDelegate = true;
}
