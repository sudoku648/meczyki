<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class DelegateDto extends PersonDto
{
    #[Assert\Type('boolean')]
    public ?bool $isDelegate = true;
}
