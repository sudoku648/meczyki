<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Tests\Form;

use Sudoku648\Meczyki\Person\Frontend\Dto\EditPersonalInfoDto;
use Sudoku648\Meczyki\Person\Frontend\Form\PersonPersonalInfoType;
use Sudoku648\Meczyki\Shared\Frontend\Form\DataTransformer\IbanTransformer;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class PersonPersonalInfoTypeTest extends TypeTestCase
{
    private IbanTransformer $ibanTransformer;

    protected function setUp(): void
    {
        $this->ibanTransformer = new IbanTransformer();

        parent::setUp();
    }

    protected function getExtensions(): array
    {
        $type = new PersonPersonalInfoType($this->ibanTransformer);

        return [
            new PreloadedExtension([$type], []),
        ];
    }

    public function testSubmitValidData(): void
    {
        $formData = [
            'iban' => '10105000997603123456789123',
        ];

        $model = new EditPersonalInfoDto();
        $form  = $this->factory->create(PersonPersonalInfoType::class, $model);

        $expected       = new EditPersonalInfoDto();
        $expected->iban = 'PL' . $formData['iban'];

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $model);
    }
}
