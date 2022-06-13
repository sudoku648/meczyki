<?php

declare(strict_types=1);

namespace App\Tests\Form;

use App\Dto\DelegateDto;
use App\Form\DataTransformer\PolishMobilePhoneTransformer;
use App\Form\DelegateType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class DelegateTypeTest extends TypeTestCase
{
    private PolishMobilePhoneTransformer $mobilePhoneTransformer;

    protected function setUp(): void
    {
        $this->mobilePhoneTransformer = new PolishMobilePhoneTransformer();

        parent::setUp();
    }

    protected function getExtensions(): array
    {
        $type = new DelegateType($this->mobilePhoneTransformer);

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

        $model = new DelegateDto();
        $form = $this->factory->create(DelegateType::class, $model);

        $expected = new DelegateDto();
        $expected->firstName         = $formData['firstName'];
        $expected->lastName          = $formData['lastName'];
        $expected->mobilePhone       = '+48'.$formData['mobilePhone'];
        $expected->isDelegate        = true;
        $expected->isReferee         = false;
        $expected->isRefereeObserver = false;

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $model);
    }
}
