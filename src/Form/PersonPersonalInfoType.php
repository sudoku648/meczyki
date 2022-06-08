<?php

declare(strict_types=1);

namespace App\Form;

use App\Dto\PersonDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonPersonalInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'dateOfBirth',
                DateType::class,
                [
                    'input'    => 'datetime_immutable',
                    'required' => false,
                    'widget'   => 'single_text',
                ]
            )
            ->add('placeOfBirth')
            ->add('addressTown')
            ->add('addressStreet')
            ->add('addressZipCode')
            ->add('addressPostOffice')
            ->add(
                'addressVoivodeship',
                ChoiceType::class,
                [
                    'choices'  => [
                        'dolnośląskie'        => 'dolnośląskie',
                        'kujawsko-pomorskie'  => 'kujawsko-pomorskie',
                        'lubelskie'           => 'lubelskie',
                        'lubuskie'            => 'lubuskie',
                        'łódzkie'             => 'łódzkie',
                        'małopolskie'         => 'małopolskie',
                        'mazowieckie'         => 'mazowieckie',
                        'opolskie'            => 'opolskie',
                        'podkarpackie'        => 'podkarpackie',
                        'podlaskie'           => 'podlaskie',
                        'pomorskie'           => 'pomorskie',
                        'śląskie'             => 'śląskie',
                        'świętokrzyskie'      => 'świętokrzyskie',
                        'warmińsko-mazurskie' => 'warmińsko-mazurskie',
                        'wielkopolskie'       => 'wielkopolskie',
                        'zachodniopomorskie'  => 'zachodniopomorskie',
                    ],
                    'required' => false,
                ]
            )
            ->add('addressPowiat')
            ->add('addressGmina')
            ->add('taxOfficeName')
            ->add('taxOfficeAddress')
            ->add('pesel')
            ->add('nip')
            ->add('bankAccountNumber')
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PersonDto::class
        ]);
    }
}
