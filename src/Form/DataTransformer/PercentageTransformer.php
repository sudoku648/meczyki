<?php

declare(strict_types=1);

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class PercentageTransformer implements DataTransformerInterface
{
    /**
     * Transforms percent number value to a fraction.
     *
     * @param string|null $percent
     */
    public function transform($percent): ?float
    {
        if (null === $percent) {
            return null;
        }

        return $percent / 100;
    }

    /**
     * Transforms percent as a fraction to number value.
     *
     * @param string|null $fraction
     */
    public function reverseTransform($fraction): ?float
    {
        if (!$fraction) {
            return null;
        }

        return $fraction * 100;
    }
}
