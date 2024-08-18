<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Dto;

use Sudoku648\Meczyki\Person\Frontend\Validator\Constraints as PersonAssert;

#[PersonAssert\PersonUnique]
final class CreatePersonDto
{
    public function __construct(
        #[PersonAssert\FirstNameRequirements]
        public ?string $firstName = null,
        #[PersonAssert\LastNameRequirements]
        public ?string $lastName = null,
        #[PersonAssert\MobilePhoneRequirements]
        public ?string $mobilePhone = null,
        public array $functions = [],
    ) {
    }
}
