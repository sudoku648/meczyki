<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraints\Regex;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class PolishZipCode extends Regex
{
    public function __construct(
        string $message = null,
        string $htmlPattern = null,
        callable $normalizer = null,
        array $groups = null,
        mixed $payload = null,
        array $options = []
    ) {
        $pattern = '/^[0-9]{2}-[0-9]{3}$/Du';

        parent::__construct(
            $pattern, $message, $htmlPattern, $match = true,
            $normalizer, $groups, $payload, $options
        );
    }
}
