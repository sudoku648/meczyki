<?php

declare(strict_types=1);

namespace App\Form;

use App\Dto\RefereeObserverDto;
use App\Form\DataTransformer\PolishMobilePhoneTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RefereeObserverType extends AbstractType
{
    private PolishMobilePhoneTransformer $mobilePhoneTransformer;

    public function __construct(PolishMobilePhoneTransformer $mobilePhoneTransformer)
    {
        $this->mobilePhoneTransformer = $mobilePhoneTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add(
                'mobilePhone',
                TelType::class,
                [
                    'required' => false,
                ]
            )
            ->add('save', SubmitType::class)
            ->add('saveAndContinue', SubmitType::class)
        ;

        $builder->get('mobilePhone')
            ->addModelTransformer($this->mobilePhoneTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RefereeObserverDto::class
        ]);
    }
}
