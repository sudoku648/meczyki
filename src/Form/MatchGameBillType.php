<?php

declare(strict_types=1);

namespace App\Form;

use App\Dto\MatchGameBillDto;
use App\Form\DataTransformer\PercentageTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatchGameBillType extends AbstractType
{
    private PercentageTransformer $percentageTransformer;

    public function __construct(PercentageTransformer $percentageTransformer)
    {
        $this->percentageTransformer = $percentageTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $builder->getData();

        $builder
            ->add(
                'baseEquivalent',
                IntegerType::class,
                [
                    'attr' => [
                        'min' => 0,
                    ],
                ]
            )
            ->add(
                'percentOfBaseEquivalent',
                PercentType::class,
                [
                    'attr' => [
                        'max'   => 100,
                        'min'   => 0,
                        'value' => $data ? $data->percentOfBaseEquivalent : 100
                    ],
                ]
            )
            ->add(
                'taxDeductibleStakePercent',
                PercentType::class,
                [
                    'attr' => [
                        'max'   => 100,
                        'min'   => 0,
                        'value' => $data ? $data->taxDeductibleStakePercent : 20
                    ],
                ]
            )
            ->add(
                'incomeTaxStakePercent',
                PercentType::class,
                [
                    'attr' => [
                        'max'   => 100,
                        'min'   => 0,
                        'value' => $data ? $data->incomeTaxStakePercent : 17
                    ],
                ]
            )
            ->add('save', SubmitType::class)
        ;

        $builder->get('percentOfBaseEquivalent')
            ->addModelTransformer($this->percentageTransformer);
        $builder->get('taxDeductibleStakePercent')
            ->addModelTransformer($this->percentageTransformer);
        $builder->get('incomeTaxStakePercent')
            ->addModelTransformer($this->percentageTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MatchGameBillDto::class
        ]);
    }
}