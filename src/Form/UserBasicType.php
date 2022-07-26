<?php

declare(strict_types=1);

namespace App\Form;

use App\Dto\UserDto;
use App\Entity\UserRole;
use App\Repository\UserRoleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserBasicType extends AbstractType
{
    private UserRoleRepository $userRoleRepository;

    public function __construct(
        UserRoleRepository $userRoleRepository
    )
    {
        $this->userRoleRepository = $userRoleRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add(
                'userRoles',
                EntityType::class,
                [
                    'choice_label' => function (UserRole $userRole) {
                        return $userRole->getName();
                    },
                    'choices'      => $this->userRoleRepository->allOrderedByName(),
                    'class'        => UserRole::class,
                    'expanded'     => true,
                    'multiple'     => true,
                    'required'     => false,
                ]
            )
            ->add('save', SubmitType::class)
            ->add('saveAndContinue', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => UserDto::class,
            ]
        );
    }
}
