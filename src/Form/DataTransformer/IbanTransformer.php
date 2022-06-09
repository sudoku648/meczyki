<?php

declare(strict_types=1);

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class IbanTransformer implements DataTransformerInterface
{
    /**
     * Transforms IBAN with country code to IBAN without it.
     *
     * @param string|null $iban
     */
    public function transform($iban): ?string
    {
        if (null === $iban) {
            return null;
        }

        return \substr($iban, 2);
    }

    /**
     * Transforms IBAN without country code to IBAN with it.
     *
     * @param string|null $nrb
     */
    public function reverseTransform($nrb): ?string
    {
        if (!$nrb) {
            return null;
        }

        return 'PL'.$nrb;
    }
}
