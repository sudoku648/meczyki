<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Frontend\Form;

use Sudoku648\Meczyki\MatchGameBill\Frontend\Dto\MatchGameBillDto;
use Sudoku648\Meczyki\Person\Domain\ValueObject\MatchGameFunction;
use Sudoku648\Meczyki\Shared\Frontend\Form\DataTransformer\PercentageTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatchGameBillType extends AbstractType
{
    public function __construct(
        private readonly PercentageTransformer $percentageTransformer,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $builder->getData();

        $builder
            ->add(
                'matchGameFunction',
                EnumType::class,
                [
                    'choice_label'              => fn (MatchGameFunction $function) => $function->getName(),
                    'choice_translation_domain' => 'Person',
                    'class'                     => MatchGameFunction::class,
                ]
            )
            ->add(
                'baseEquivalent',
                MoneyType::class,
                [
                    'attr' => [
                        'min' => 0,
                    ],
                    'currency' => 'PLN',
                ]
            )
            ->add(
                'percentOfBaseEquivalent',
                PercentType::class,
                [
                    'attr' => [
                        'max'   => 100,
                        'min'   => 0,
                        'value' => $data ? $data->percentOfBaseEquivalent : 100,
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
                        'value' => $data ? $data->taxDeductibleStakePercent : 20,
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
                        'value' => $data ? $data->incomeTaxStakePercent : 12,
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
            'data_class'         => MatchGameBillDto::class,
            'translation_domain' => 'MatchGameBill',
        ]);
    }
}
