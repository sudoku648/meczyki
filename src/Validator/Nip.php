<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Nip extends Constraint
{
    public const TOO_SHORT_ERROR = 'e2e3f2ef-0a1d-46c8-99a2-4e704c87fc76';
    public const TOO_LONG_ERROR = '619eb81a-2602-4351-a7bf-24369e8139e5';
    public const INVALID_CHARACTERS_ERROR = 'b3071b04-c70c-4c36-9ad2-028a02630e35';
    public const CHECKSUM_FAILED_ERROR = '4aa12d87-2097-4020-8df2-c72bbf9e7046';

    protected const ERROR_NAMES = [
        self::TOO_SHORT_ERROR => 'TOO_SHORT_ERROR',
        self::TOO_LONG_ERROR => 'TOO_LONG_ERROR',
        self::INVALID_CHARACTERS_ERROR => 'INVALID_CHARACTERS_ERROR',
        self::CHECKSUM_FAILED_ERROR => 'CHECKSUM_FAILED_ERROR',
    ];

    public $message = 'Nieprawidłowy NIP.';

    public function __construct(
        array $options = null,
        string $message = null,
        array $groups = null,
        mixed $payload = null
    )
    {
        parent::__construct($options, $groups, $payload);

        $this->message = $message ?? $this->message;
    }
}
