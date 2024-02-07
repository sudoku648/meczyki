<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Tests\Form;

use Sudoku648\Meczyki\Person\Frontend\Dto\PersonDto;
use Sudoku648\Meczyki\Person\Frontend\Form\RefereeType;
use Sudoku648\Meczyki\Shared\Frontend\Form\DataTransformer\PolishMobilePhoneTransformer;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class RefereeTypeTest extends TypeTestCase
{
    private PolishMobilePhoneTransformer $mobilePhoneTransformer;

    protected function setUp(): void
    {
        $this->mobilePhoneTransformer = new PolishMobilePhoneTransformer();

        parent::setUp();
    }

    protected function getExtensions(): array
    {
        $type = new RefereeType($this->mobilePhoneTransformer);

        return [
            new PreloadedExtension([$type], []),
        ];
    }

    public function testSubmitValidData(): void
    {
        $formData = [
            'firstName'   => 'John',
            'lastName'    => 'Doe',
            'mobilePhone' => '520123456',
        ];

        $model            = new PersonDto();
        $model->isReferee = true;
        $form             = $this->factory->create(RefereeType::class, $model);

        $expected                    = new PersonDto();
        $expected->firstName         = $formData['firstName'];
        $expected->lastName          = $formData['lastName'];
        $expected->mobilePhone       = '+48' . $formData['mobilePhone'];
        $expected->isDelegate        = false;
        $expected->isReferee         = true;
        $expected->isRefereeObserver = false;

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $model);
    }
}
