<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\Form;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Domain\Persistence\PersonRepositoryInterface;
use Sudoku648\Meczyki\User\Frontend\Dto\UserDto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserBindWithPersonType extends AbstractType
{
    public function __construct(
        private readonly PersonRepositoryInterface $personRepository,
    ) {
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
            'data_class'         => UserDto::class,
            'translation_domain' => 'User',
        ]);
    }
}
