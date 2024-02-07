<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Tests\Validator;

use Sudoku648\Meczyki\Shared\Frontend\Validator\Constraints\PolishMobilePhone;
use Sudoku648\Meczyki\Shared\Frontend\Validator\Constraints\PolishMobilePhoneValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class PolishMobilePhoneValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): PolishMobilePhoneValidator
    {
        return new PolishMobilePhoneValidator();
    }

    public function testNullIsValid(): void
    {
        $this->validator->validate(null, new PolishMobilePhone());

        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid(): void
    {
        $this->validator->validate('', new PolishMobilePhone());

        $this->assertNoViolation();
    }

    /**
     * @dataProvider getValidValues
     */
    public function testValidValues($value): void
    {
        $this->validator->validate($value, new PolishMobilePhone());

        $this->assertNoViolation();
    }

    /**
     * @dataProvider getInvalidValues
     */
    public function testInvalidValues($value): void
    {
        $constraint = new PolishMobilePhone('myMessage');

        $this->validator->validate($value, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"' . $value . '"')
            ->setCode(PolishMobilePhone::REGEX_FAILED_ERROR)
            ->assertRaised();
    }

    public function getValidValues(): iterable
    {
        yield ['+48451123456'];
        yield ['+48502123456'];
        yield ['+48695123456'];
        yield ['+48880123456'];
    }

    public function getInvalidValues(): iterable
    {
        yield ['520123456'];
        yield ['+48520123456'];
        yield ['+48700123456'];
        yield ['+48830123456'];
    }
}
