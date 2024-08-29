<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_CLASS)]
final class PersonUnique extends Constraint
{
    protected const string MOBILE_PHONE_EXISTS_MESSAGE = 'Mobile phone already belongs to another person.';

    public function __construct(
        public string $mobilePhoneExists = self::MOBILE_PHONE_EXISTS_MESSAGE,
        mixed $options = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct($options, $groups, $payload);
    }

    public function getTargets(): string|array
    {
        return parent::CLASS_CONSTRAINT;
    }
}
