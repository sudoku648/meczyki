<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Frontend\Form;

use Sudoku648\Meczyki\GameType\Frontend\Dto\GameTypeDto;
use Sudoku648\Meczyki\Image\Frontend\Form\Constraint\ImageConstraint;
use Sudoku648\Meczyki\Shared\Frontend\Form\EventListener\ImageListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameTypeType extends AbstractType
{
    public function __construct(
        private readonly ImageListener $imageListener,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add(
                'isOfficial',
                CheckboxType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'image',
                FileType::class,
                [
                    'constraints' => ImageConstraint::default(),
                    'mapped'      => false,
                    'required'    => false,
                ],
            )
            ->add('save', SubmitType::class)
            ->add('saveAndContinue', SubmitType::class)
        ;

        $builder->addEventSubscriber($this->imageListener);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'         => GameTypeDto::class,
            'translation_domain' => 'GameType',
        ]);
    }
}
