<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Frontend\Form;

use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;
use Sudoku648\Meczyki\GameType\Domain\Persistence\GameTypeRepositoryInterface;
use Sudoku648\Meczyki\MatchGame\Frontend\Dto\MatchGameDto;
use Sudoku648\Meczyki\Person\Domain\Persistence\PersonRepositoryInterface;
use Sudoku648\Meczyki\Person\Frontend\Form\Option\PersonOption;
use Sudoku648\Meczyki\Team\Domain\Entity\Team;
use Sudoku648\Meczyki\Team\Domain\Persistence\TeamRepositoryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use function array_merge;

class MatchGameType extends AbstractType
{
    public function __construct(
        private readonly GameTypeRepositoryInterface $gameTypeRepository,
        private readonly PersonRepositoryInterface $personRepository,
        private readonly TeamRepositoryInterface $teamRepository
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'homeTeam',
                EntityType::class,
                [
                    'autocomplete' => true,
                    'choice_label' => function (Team $team) {
                        return $team->getName();
                    },
                    'choices'      => $this->teamRepository->allOrderedByName(),
                    'class'        => Team::class,
                ]
            )
            ->add(
                'awayTeam',
                EntityType::class,
                [
                    'autocomplete' => true,
                    'choice_label' => function (Team $team) {
                        return $team->getName();
                    },
                    'choices'      => $this->teamRepository->allOrderedByName(),
                    'class'        => Team::class,
                ]
            )
            ->add(
                'dateTime',
                DateTimeType::class,
                [
                    'date_widget'    => 'single_text',
                    'input'          => 'datetime_immutable',
                    'model_timezone' => 'Europe/Warsaw',
                    'time_widget'    => 'single_text',
                ]
            )
            ->add(
                'gameType',
                EntityType::class,
                [
                    'choice_label' => function (GameType $gameType) {
                        return $gameType->getName();
                    },
                    'choices'      => $this->gameTypeRepository->allOrderedByName(),
                    'class'        => GameType::class,
                ]
            )
            ->add(
                'season',
                ChoiceType::class,
                [
                    'choices'  => [
                        '2017/2018' => '2017/2018',
                        '2018/2019' => '2018/2019',
                        '2019/2020' => '2019/2020',
                        '2020/2021' => '2020/2021',
                        '2021/2022' => '2021/2022',
                        '2022/2023' => '2022/2023',
                    ],
                    'required' => false,
                ]
            )
            ->add(
                'round',
                IntegerType::class,
                [
                    'attr' => [
                        'min' => 1,
                    ],
                    'required' => false,
                ]
            )
            ->add('venue')
            ->add(
                'referee',
                EntityType::class,
                array_merge(
                    PersonOption::default($this->personRepository, 'referee'),
                    [
                        'autocomplete' => true,
                    ]
                ),
            )
            ->add(
                'firstAssistantReferee',
                EntityType::class,
                array_merge(
                    PersonOption::default($this->personRepository, 'referee'),
                    [
                        'autocomplete' => true,
                        'required'     => false,
                    ]
                ),
            )
            ->add(
                'isFirstAssistantNonProfitable',
                CheckboxType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'secondAssistantReferee',
                EntityType::class,
                array_merge(
                    PersonOption::default($this->personRepository, 'referee'),
                    [
                        'autocomplete' => true,
                        'required'     => false,
                    ]
                ),
            )
            ->add(
                'isSecondAssistantNonProfitable',
                CheckboxType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'fourthOfficial',
                EntityType::class,
                array_merge(
                    PersonOption::default($this->personRepository, 'referee'),
                    [
                        'autocomplete' => true,
                        'required'     => false,
                    ]
                ),
            )
            ->add(
                'refereeObserver',
                EntityType::class,
                array_merge(
                    PersonOption::default($this->personRepository, 'refereeObserver'),
                    [
                        'autocomplete' => true,
                        'required'     => false,
                    ]
                ),
            )
            ->add(
                'delegate',
                EntityType::class,
                array_merge(
                    PersonOption::default($this->personRepository, 'delegate'),
                    [
                        'autocomplete' => true,
                        'required'     => false,
                    ]
                ),
            )
            ->add(
                'moreInfo',
                TextareaType::class,
                [
                    'required' => false,
                ]
            )
            ->add('save', SubmitType::class)
            ->add('saveAndContinue', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MatchGameDto::class
        ]);
    }
}
