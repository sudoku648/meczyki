<?php

declare(strict_types=1);

namespace App\Form;

use App\Dto\UserBindWithPersonDto;
use App\Entity\Person;
use App\Repository\PersonRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserBindWithPersonType extends AbstractType
{
    private PersonRepository $personRepository;

    public function __construct(
        PersonRepository $personRepository
    )
    {
        $this->personRepository = $personRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'person',
                EntityType::class,
                [
                    'choice_label' => function (Person $person) {
                        return $person->getFullName();
                    },
                    'choices'      => $this->personRepository->allOrderedByName(),
                    'class'        => Person::class,
                    'required'     => false,
                ]
            )
            ->add('save', SubmitType::class)
            ->add('saveAndContinue', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserBindWithPersonDto::class
        ]);
    }
}