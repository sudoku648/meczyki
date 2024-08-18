<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Frontend\Form;

use Sudoku648\Meczyki\Team\Frontend\Dto\TeamDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('shortName')
            ->add('name')
            ->add('save', SubmitType::class)
            ->add('saveAndContinue', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'         => TeamDto::class,
            'translation_domain' => 'Team',
        ]);
    }
}
