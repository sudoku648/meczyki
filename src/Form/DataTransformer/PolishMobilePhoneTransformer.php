<?php

declare(strict_types=1);

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class PolishMobilePhoneTransformer implements DataTransformerInterface
{
    public const POLISH_DIALLING_CODE = '+48';

    /**
     * Transforms number with country dialling code to number without it.
     *
     * @param string|null $codefullNumber
     */
    public function transform($codefullNumber): ?string
    {
        if (null === $codefullNumber) {
            return null;
        }

        return \substr($codefullNumber, \strlen(self::POLISH_DIALLING_CODE));
    }

    /**
     * Transforms number without country dialling code to number with it.
     *
     * @param string|null $codelessNumber
     */
    public function reverseTransform($codelessNumber): ?string
    {
        if (!$codelessNumber) {
            return null;
        }

        return self::POLISH_DIALLING_CODE.$codelessNumber;
    }
}
