<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Frontend\Twig;

use BadMethodCallException;
use InvalidArgumentException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

use function constant;
use function defined;
use function enum_exists;
use function method_exists;
use function sprintf;

class EnumExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('enum', [$this, 'enum']),
        ];
    }

    public function enum(string $enumFQN): object
    {
        return new readonly class ($enumFQN) {
            public function __construct(private string $enum)
            {
                if (!enum_exists($this->enum)) {
                    throw new InvalidArgumentException(sprintf(
                        '%s is not an Enum type and cannot be used in this function',
                        $this->enum,
                    ));
                }
            }

            public function __call(string $name, array $arguments)
            {
                $enumFQN = sprintf('%s::%s', $this->enum, $name);

                if (defined($enumFQN)) {
                    return constant($enumFQN);
                }

                if (method_exists($this->enum, $name)) {
                    return $this->enum::$name(...$arguments);
                }

                throw new BadMethodCallException(sprintf(
                    'Neither "%s" nor "%s::%s()" exist in this runtime.',
                    $enumFQN,
                    $enumFQN,
                    $name,
                ));
            }
        };
    }
}
