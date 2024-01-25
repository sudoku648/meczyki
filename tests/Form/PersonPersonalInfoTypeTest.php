<?php

declare(strict_types=1);

namespace App\Tests\Form;

use App\Dto\PersonDto;
use App\Form\DataTransformer\IbanTransformer;
use App\Form\PersonPersonalInfoType;
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

        $model = new PersonDto();
        $form  = $this->factory->create(PersonPersonalInfoType::class, $model);

        $expected       = new PersonDto();
        $expected->iban = 'PL' . $formData['iban'];

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $model);
    }
}
