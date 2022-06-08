<?php

declare(strict_types=1);

namespace App\Tests\Validator;

use App\Validator\Pesel;
use App\Validator\PeselValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class PeselValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): PeselValidator
    {
        return new PeselValidator();
    }

    public function testNullIsValid(): void
    {
        $this->validator->validate(null, new Pesel());

        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid(): void
    {
        $this->validator->validate('', new Pesel());

        $this->assertNoViolation();
    }

    /**
     * @dataProvider getValidValues
     */
    public function testValidValues($value): void
    {
        $this->validator->validate($value, new Pesel());

        $this->assertNoViolation();
    }

    /**
     * @dataProvider getTooShortValues
     */
    public function testTooShortValues($value): void
    {
        $constraint = new Pesel([], 'myMessage');

        $this->validator->validate($value, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"'.$value.'"')
            ->setCode(Pesel::TOO_SHORT_ERROR)
            ->assertRaised();
    }

    /**
     * @dataProvider getTooLongValues
     */
    public function testTooLongValues($value): void
    {
        $constraint = new Pesel([], 'myMessage');

        $this->validator->validate($value, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"'.$value.'"')
            ->setCode(Pesel::TOO_LONG_ERROR)
            ->assertRaised();
    }

    /**
     * @dataProvider getNotNumericValues
     */
    public function testNotNumericValues($value): void
    {
        $constraint = new Pesel([], 'myMessage');

        $this->validator->validate($value, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"'.$value.'"')
            ->setCode(Pesel::INVALID_CHARACTERS_ERROR)
            ->assertRaised();
    }

    /**
     * @dataProvider getInvalidChecksumValues
     */
    public function testInvalidChecksumValues($value): void
    {
        $constraint = new Pesel([], 'myMessage');

        $this->validator->validate($value, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"'.$value.'"')
            ->setCode(Pesel::CHECKSUM_FAILED_ERROR)
            ->assertRaised();
    }

    /**
     * @dataProvider getInvalidDateOfBirthValues
     */
    public function testInvalidDateOfBirthValues($value): void
    {
        $constraint = new Pesel([], 'myMessage');

        $this->validator->validate($value, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"'.$value.'"')
            ->setCode(Pesel::DATE_OF_BIRTH_ERROR)
            ->assertRaised();
    }

    public function getValidValues(): iterable
    {
        yield ['49082098185'];
        yield ['56120835224'];
        yield ['66100387155'];
        yield ['76091194414'];
        yield ['76040951985'];
        yield ['99841347827'];
        yield ['02212187571'];
        yield ['32451585131'];
    }

    public function getTooShortValues(): iterable
    {
        yield ['490820981'];
        yield ['5612083522'];
    }

    public function getTooLongValues(): iterable
    {
        yield ['490820981852'];
        yield ['5612083522456'];
    }

    public function getNotNumericValues(): iterable
    {
        yield ['49082a98185'];
        yield ['561208352-4'];
        yield ['66/00387155'];
    }

    public function getInvalidChecksumValues(): iterable
    {
        yield ['49082098188'];
        yield ['56120835226'];
        yield ['66100387151'];
    }

    public function getInvalidDateOfBirthValues(): iterable
    {
        yield ['49083298188'];
        yield ['55022935229'];
        yield ['66930387152'];
    }
}
