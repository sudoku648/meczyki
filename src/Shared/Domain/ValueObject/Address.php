<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Domain\ValueObject;

final readonly class Address implements ValueObjectInterface
{
    public function __construct(
        private ?string $town = null,
        private ?string $street = null,
        private ?string $postCode = null,
        private ?string $postOffice = null,
        private ?Voivodeship $voivodeship = null,
        private ?string $county = null,
        private ?string $gmina = null,
    ) {
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    public function getPostOffice(): ?string
    {
        return $this->postOffice;
    }

    public function getVoivodeship(): ?Voivodeship
    {
        return $this->voivodeship;
    }

    public function getCounty(): ?string
    {
        return $this->county;
    }

    public function getGmina(): ?string
    {
        return $this->gmina;
    }

    public function formatted(): string
    {
        $address = '';

        if ($this->town) {
            $address .= $this->town;
        }
        if ($this->street) {
            $address .= '' !== $address ? ', ' . $this->street : $this->street;
        }
        if ($this->postCode) {
            $address .= '' !== $address ? ', ' . $this->postCode : $this->postCode;
        }
        if ($this->postOffice && $this->postCode) {
            $address .= ' ' . $this->postOffice;
        } else {
            $address .= '' !== $address ? ', ' . $this->postOffice : $this->postOffice;
        }

        return $address;
    }

    public function equals(ValueObjectInterface $object): bool
    {
        if (!$object instanceof static) {
            return false;
        }

        return $this->getValue() === $object->getValue();
    }

    public function getValue(): array
    {
        return [
            'town'        => $this->town,
            'street'      => $this->street,
            'postCode'    => $this->postCode,
            'postOffice'  => $this->postOffice,
            'voivodeship' => $this->voivodeship,
            'county'      => $this->county,
            'gmina'       => $this->gmina,
        ];
    }
}
