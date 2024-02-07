<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Tests\Validator;

use Sudoku648\Meczyki\Shared\Frontend\Validator\Constraints\PolishPostCode;
use Sudoku648\Meczyki\Shared\Frontend\Validator\Constraints\PolishPostCodeValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class PolishPostCodeValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): PolishPostCodeValidator
    {
        return new PolishPostCodeValidator();
    }

    public function testNullIsValid(): void
    {
        $this->validator->validate(null, new PolishPostCode());

        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid(): void
    {
        $this->validator->validate('', new PolishPostCode());

        $this->assertNoViolation();
    }

    /**
     * @dataProvider getValidValues
     */
    public function testValidValues($value): void
    {
        $this->validator->validate($value, new PolishPostCode());

        $this->assertNoViolation();
    }

    /**
     * @dataProvider getInvalidValues
     */
    public function testInvalidValues($value): void
    {
        $constraint = new PolishPostCode('myMessage');

        $this->validator->validate($value, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"' . $value . '"')
            ->setCode(PolishPostCode::REGEX_FAILED_ERROR)
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
