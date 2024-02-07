<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Form;

use Sudoku648\Meczyki\Person\Frontend\Dto\PersonDto;
use Sudoku648\Meczyki\Shared\Domain\Enums\Voivodeship;
use Sudoku648\Meczyki\Shared\Frontend\Form\DataTransformer\IbanTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonPersonalInfoType extends AbstractType
{
    public function __construct(
        private readonly IbanTransformer $ibanTransformer,
    ) {
    }

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
            ->add('addressPostCode')
            ->add('addressPostOffice')
            ->add(
                'addressVoivodeship',
                EnumType::class,
                [
                    'class'        => Voivodeship::class,
                    'choice_label' => function (Voivodeship $voivodeship) {
                        return $voivodeship->getName();
                    },
                    'required'     => false,
                ]
            )
            ->add('addressCounty')
            ->add('addressGmina')
            ->add('taxOfficeName')
            ->add('taxOfficeAddress')
            ->add('pesel')
            ->add('nip')
            ->add('iban')
            ->add(
                'allowsToSendPitByEmail',
                CheckboxType::class,
                [
                    'required' => false,
                ]
            )
            ->add('save', SubmitType::class)
        ;

        $builder->get('iban')
            ->addModelTransformer($this->ibanTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PersonDto::class
        ]);
    }
}
