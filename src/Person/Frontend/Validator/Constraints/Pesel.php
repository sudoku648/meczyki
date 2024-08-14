<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Pesel extends Constraint
{
    public const string TOO_SHORT_ERROR          = '273266e5-bed0-4ae5-959a-0f21cbabf669';
    public const string TOO_LONG_ERROR           = '27762203-f6b5-407f-96ca-bf927bfb5786';
    public const string INVALID_CHARACTERS_ERROR = '394ecd7b-de74-4ff3-a44f-4b7142cd395c';
    public const string CHECKSUM_FAILED_ERROR    = '0dc5e9bc-02be-49a6-9ab7-244118ab8793';
    public const string DATE_OF_BIRTH_ERROR      = 'fd7e0ef4-72b1-41ea-a873-2be92e8ea1d2';

    protected const array ERROR_NAMES = [
        self::TOO_SHORT_ERROR          => 'TOO_SHORT_ERROR',
        self::TOO_LONG_ERROR           => 'TOO_LONG_ERROR',
        self::INVALID_CHARACTERS_ERROR => 'INVALID_CHARACTERS_ERROR',
        self::CHECKSUM_FAILED_ERROR    => 'CHECKSUM_FAILED_ERROR',
        self::DATE_OF_BIRTH_ERROR      => 'DATE_OF_BIRTH_ERROR',
    ];

    public string $message = 'Invalid PESEL.';

    public function __construct(
        array $options = null,
        string $message = null,
        array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct($options, $groups, $payload);

        $this->message = $message ?? $this->message;
    }
}
