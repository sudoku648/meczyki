<?php

declare(strict_types=1);

namespace App\Form;

use App\Dto\UserRoleDto;
use App\Entity\Enums\PermissionEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add(
                'permissions',
                EnumType::class,
                [
                    'choice_label' => function (PermissionEnum $permission) {
                        return $permission->getLabel();
                    },
                    'class'        => PermissionEnum::class,
                    'expanded'     => true,
                    'multiple'     => true,
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
            'data_class' => UserRoleDto::class
        ]);
    }
}
