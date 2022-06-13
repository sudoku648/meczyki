<?php

declare(strict_types=1);

namespace App\Tests\Form;

use App\Dto\RefereeObserverDto;
use App\Form\DataTransformer\PolishMobilePhoneTransformer;
use App\Form\RefereeObserverType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

class RefereeObserverTypeTest extends TypeTestCase
{
    private PolishMobilePhoneTransformer $mobilePhoneTransformer;

    protected function setUp(): void
    {
        $this->mobilePhoneTransformer = new PolishMobilePhoneTransformer();

        parent::setUp();
    }

    protected function getExtensions(): array
    {
        $type = new RefereeObserverType($this->mobilePhoneTransformer);

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

        $model = new RefereeObserverDto();
        $form = $this->factory->create(RefereeObserverType::class, $model);

        $expected = new RefereeObserverDto();
        $expected->firstName         = $formData['firstName'];
        $expected->lastName          = $formData['lastName'];
        $expected->mobilePhone       = '+48'.$formData['mobilePhone'];
        $expected->isDelegate        = false;
        $expected->isReferee         = false;
        $expected->isRefereeObserver = true;

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $model);
    }
}
