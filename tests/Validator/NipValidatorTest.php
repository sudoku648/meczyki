<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Tests\Validator;

use Sudoku648\Meczyki\Shared\Frontend\Validator\Constraints\Nip;
use Sudoku648\Meczyki\Shared\Frontend\Validator\Constraints\NipValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class NipValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): NipValidator
    {
        return new NipValidator();
    }

    public function testNullIsValid(): void
    {
        $this->validator->validate(null, new Nip());

        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid(): void
    {
        $this->validator->validate('', new Nip());

        $this->assertNoViolation();
    }

    /**
     * @dataProvider getValidValues
     */
    public function testValidValues($value): void
    {
        $this->validator->validate($value, new Nip());

        $this->assertNoViolation();
    }

    /**
     * @dataProvider getTooShortValues
     */
    public function testTooShortValues($value): void
    {
        $constraint = new Nip([], 'myMessage');

        $this->validator->validate($value, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"' . $value . '"')
            ->setCode(Nip::TOO_SHORT_ERROR)
            ->assertRaised();
    }

    /**
     * @dataProvider getTooLongValues
     */
    public function testTooLongValues($value): void
    {
        $constraint = new Nip([], 'myMessage');

        $this->validator->validate($value, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"' . $value . '"')
            ->setCode(Nip::TOO_LONG_ERROR)
            ->assertRaised();
    }

    /**
     * @dataProvider getNotNumericValues
     */
    public function testNotNumericValues($value): void
    {
        $constraint = new Nip([], 'myMessage');

        $this->validator->validate($value, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"' . $value . '"')
            ->setCode(Nip::INVALID_CHARACTERS_ERROR)
            ->assertRaised();
    }

    /**
     * @dataProvider getInvalidChecksumValues
     */
    public function testInvalidChecksumValues($value): void
    {
        $constraint = new Nip([], 'myMessage');

        $this->validator->validate($value, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"' . $value . '"')
            ->setCode(Nip::CHECKSUM_FAILED_ERROR)
            ->assertRaised();
    }

    public function getValidValues(): iterable
    {
        yield ['8223629265'];
        yield ['5253871118'];
        yield ['7599696165'];
        yield ['3811071024'];
        yield ['4161668449'];
        yield ['4963355408'];
        yield ['8233112262'];
        yield ['1128573017'];
    }

    public function getTooShortValues(): iterable
    {
        yield ['49082098'];
        yield ['561208352'];
    }

    public function getTooLongValues(): iterable
    {
        yield ['49082098185'];
        yield ['561208352245'];
    }

    public function getNotNumericValues(): iterable
    {
        yield ['49082a9818'];
        yield ['56120852-4'];
        yield ['66/0087155'];
    }

    public function getInvalidChecksumValues(): iterable
    {
        yield ['8223629268'];
        yield ['5253871112'];
        yield ['7599696161'];
    }
}
