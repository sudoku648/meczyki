<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Domain\ValueObject;

use Sudoku648\Meczyki\Shared\Domain\Exception\MoneyException;
use Symfony\Component\Intl\Currencies;

use function pow;
use function round;

readonly class Money
{
    final public function __construct(
        private int $amount,
        private string $currency,
    ) {
        if (!Currencies::exists($currency)) {
            throw MoneyException::currencyDoesNotExist($currency);
        }
    }

    public static function fromDecimal(float $amount, string $currency): static
    {
        return new static((int) ($amount * pow(10, Currencies::getFractionDigits($currency))), $currency);
    }

    public static function PLN(int $amount): static
    {
        return new static($amount, 'PLN');
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function equals(self $other): bool
    {
        $this->assertSameCurrency($other);

        return $this->amount === $other->amount;
    }

    public function add(self $other): static
    {
        $this->assertSameCurrency($other);

        return new self($this->amount + $other->amount, $this->currency);
    }

    public function subtract(self $other): static
    {
        $this->assertSameCurrency($other);

        return new self($this->amount - $other->amount, $this->currency);
    }

    public function multiply(float $multiplier): static
    {
        return new self((int) round($multiplier * $this->amount), $this->currency);
    }

    private function assertSameCurrency(self $other): void
    {
        if ($this->currency !== $other->currency) {
            throw MoneyException::invalidCurrencies($this->currency, $other->currency);
        }
    }
}
