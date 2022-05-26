<?php

declare(strict_types=1);

namespace App\Form;

use App\Dto\GameTypeDto;
use App\Form\Constraint\ImageConstraint;
use App\Form\EventListener\ImageListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameTypeType extends AbstractType
{
    private ImageListener $imageListener;

    public function __construct(ImageListener $imageListener)
    {
        $this->imageListener = $imageListener;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('group')
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
            'data_class' => GameTypeDto::class
        ]);
    }
}
