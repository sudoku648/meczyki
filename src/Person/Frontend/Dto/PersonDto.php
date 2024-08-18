<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Dto;

use Sudoku648\Meczyki\Person\Domain\ValueObject\PersonId;
use Sudoku648\Meczyki\Person\Frontend\Validator\Constraints as PersonAssert;

#[PersonAssert\PersonUnique]
final class PersonDto
{
    public function __construct(
        public ?PersonId $personId = null,
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
