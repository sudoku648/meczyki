<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\DataTable\Factory;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Domain\Persistence\PersonRepositoryInterface;
use Sudoku648\Meczyki\Person\Domain\ValueObject\MatchGameFunction;
use Sudoku648\Meczyki\Person\Frontend\DataTable\Model\DataTablePersonRow;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\PersonVoter;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\Traits\DataTableOrdinalNumberTrait;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

use function array_keys;
use function array_map;
use function array_values;

readonly class DataTablePersonResponseFactory
{
    use DataTableOrdinalNumberTrait;

    public function __construct(
        private Environment $twig,
        private Security $security,
        private TranslatorInterface $translator,
        private PersonRepositoryInterface $repository,
    ) {
    }

    public function fromCriteria(Criteria $criteria): array
    {
        $objects = $this->repository->findByCriteria($criteria);

        return array_map(
            function (int $objKey, Person $person) use ($criteria) {
                return new DataTablePersonRow(
                    $this->getOrdinalNumber($objKey, $criteria),
                    $this->twig->render(
                        'person/_datatable_checkbox.html.twig',
                        [
                            'personId' => $person->getId(),
                        ]
                    ),
                    $person->getFullName(),
                    implode(
                        ', ',
                        array_map(
                            fn (string $function) => $this->translator->trans(
                                id: MatchGameFunction::from($function)->getName(),
                                domain: 'Person'
                            ),
                            $person->getFunctions()
                        )
                    ),
                    $this->getButtonsForDataTable($person)
                );
            },
            array_keys($objects),
            array_values($objects)
        );
    }

    private function getButtonsForDataTable(Person $person): string
    {
        $buttons = '';

        if ($this->security->isGranted(PersonVoter::SHOW, $person)) {
            $buttons .= $this->twig->render(
                'person/_datatable/show.html.twig',
                [
                    'personId' => $person->getId(),
                ]
            );
        }
        if ($this->security->isGranted(PersonVoter::EDIT, $person)) {
            $buttons .= $this->twig->render(
                'person/_datatable/edit.html.twig',
                [
                    'personId' => $person->getId(),
                ]
            );
        }
        if ($this->security->isGranted(PersonVoter::DELETE, $person)) {
            $buttons .= $this->twig->render(
                'person/_datatable/_delete_form.html.twig',
                [
                    'person'   => $person,
                ]
            );
        }

        return $buttons;
    }
}
