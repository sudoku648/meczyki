<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Tests\Form;

use Sudoku648\Meczyki\Person\Frontend\Dto\PersonDto;
use Sudoku648\Meczyki\Person\Frontend\Form\PersonType;
use Sudoku648\Meczyki\Shared\Frontend\Form\DataTransformer\PolishMobilePhoneTransformer;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class PersonTypeTest extends TypeTestCase
{
    private PolishMobilePhoneTransformer $mobilePhoneTransformer;

    protected function setUp(): void
    {
        $this->mobilePhoneTransformer = new PolishMobilePhoneTransformer();

        parent::setUp();
    }

    protected function getExtensions(): array
    {
        $type = new PersonType($this->mobilePhoneTransformer);

        return [
            new PreloadedExtension([$type], []),
        ];
    }

    public function testSubmitValidData(): void
    {
        $formData = [
            'firstName'         => 'John',
            'lastName'          => 'Doe',
            'mobilePhone'       => '520123456',
            'isDelegate'        => false,
            'isReferee'         => true,
            'isRefereeObserver' => false,
        ];

        $model = new PersonDto();
        $form  = $this->factory->create(PersonType::class, $model);

        $expected                    = new PersonDto();
        $expected->firstName         = $formData['firstName'];
        $expected->lastName          = $formData['lastName'];
        $expected->mobilePhone       = '+48' . $formData['mobilePhone'];
        $expected->isDelegate        = $formData['isDelegate'];
        $expected->isReferee         = $formData['isReferee'];
        $expected->isRefereeObserver = $formData['isRefereeObserver'];

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $model);
    }
}
