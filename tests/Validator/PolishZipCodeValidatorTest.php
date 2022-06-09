<?php

declare(strict_types=1);

namespace App\Tests\Validator;

use App\Validator\PolishZipCode;
use App\Validator\PolishZipCodeValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class PolishZipCodeValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): PolishZipCodeValidator
    {
        return new PolishZipCodeValidator();
    }

    public function testNullIsValid(): void
    {
        $this->validator->validate(null, new PolishZipCode());

        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid(): void
    {
        $this->validator->validate('', new PolishZipCode());

        $this->assertNoViolation();
    }

    /**
     * @dataProvider getValidValues
     */
    public function testValidValues($value): void
    {
        $this->validator->validate($value, new PolishZipCode());

        $this->assertNoViolation();
    }

    /**
     * @dataProvider getInvalidValues
     */
    public function testInvalidValues($value): void
    {
        $constraint = new PolishZipCode('myMessage');

        $this->validator->validate($value, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"'.$value.'"')
            ->setCode(PolishZipCode::REGEX_FAILED_ERROR)
            ->assertRaised();
    }

    public function getValidValues(): iterable
    {
        yield ['00-999'];
        yield ['12-154'];
        yield ['82-310'];
        yield ['11-700'];
    }

    public function getInvalidValues(): iterable
    {
        yield ['11-70'];
        yield ['00999'];
        yield ['1-184'];
        yield ['75-4289'];
        yield ['754-28'];
    }
}
