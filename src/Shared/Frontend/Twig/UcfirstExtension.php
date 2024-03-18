<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Frontend\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

use function ucfirst;

class UcfirstExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('ucfirst', [$this, 'ucfirst']),
        ];
    }

    public function ucfirst(string $string): string
    {
        return ucfirst($string);
    }
}
