<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\DataTable\Factory;

use Sudoku648\Meczyki\Security\Infrastructure\Voter\UserVoter;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\Traits\DataTableOrdinalNumberTrait;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;
use Sudoku648\Meczyki\User\Domain\Entity\User;
use Sudoku648\Meczyki\User\Domain\Persistence\UserRepositoryInterface;
use Sudoku648\Meczyki\User\Frontend\DataTable\Model\DataTableUserRow;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Environment;

use function array_keys;
use function array_map;
use function array_values;

readonly class DataTableUserResponseFactory
{
    use DataTableOrdinalNumberTrait;

    public function __construct(
        private Environment $twig,
        private Security $security,
        private UserRepositoryInterface $repository,
    ) {
    }

    public function fromCriteria(Criteria $criteria): array
    {
        $objects = $this->repository->findByCriteria($criteria);

        return array_map(
            function (int $objKey, User $user) use ($criteria) {
                return new DataTableUserRow(
                    $this->getOrdinalNumber($objKey, $criteria),
                    $this->twig->render(
                        'user/_datatable_checkbox.html.twig',
                        [
                            'userId' => $user->getId(),
                        ]
                    ),
                    $user->getUsername()->getValue(),
                    $this->getButtonsForDataTable($user)
                );
            },
            array_keys($objects),
            array_values($objects)
        );
    }

    private function getButtonsForDataTable(User $user): string
    {
        $buttons = '';

        if ($this->security->isGranted(UserVoter::SHOW, $user)) {
            $buttons .= $this->twig->render(
                'user/_datatable/show.html.twig',
                [
                    'userId' => $user->getId(),
                ]
            );
        }
        if ($this->security->isGranted(UserVoter::EDIT, $user)) {
            $buttons .= $this->twig->render(
                'user/_datatable/edit.html.twig',
                [
                    'userId' => $user->getId(),
                ]
            );
        }
        if ($this->security->isGranted(UserVoter::ACTIVATE, $user)) {
            $buttons .= $this->twig->render(
                'user/_datatable/_activate_form.html.twig',
                [
                    'user' => $user,
                ]
            );
        } elseif ($this->security->isGranted(UserVoter::DEACTIVATE, $user)) {
            $buttons .= $this->twig->render(
                'user/_datatable/_deactivate_form.html.twig',
                [
                    'user' => $user,
                ]
            );
        }
        if ($this->security->isGranted(UserVoter::BIND_WITH_PERSON, $user)) {
            $buttons .= $this->twig->render(
                'user/_datatable/bind_with_person.html.twig',
                [
                    'userId' => $user->getId(),
                ]
            );
        }
        if ($this->security->isGranted(UserVoter::IMPERSONATE, $user)) {
            $buttons .= $this->twig->render(
                'user/_datatable/impersonate.html.twig',
                [
                    'userIdentifier' => $user->getUserIdentifier(),
                ]
            );
        }
        if ($this->security->isGranted(UserVoter::DELETE, $user)) {
            $buttons .= $this->twig->render(
                'user/_datatable/_delete_form.html.twig',
                [
                    'user' => $user,
                ]
            );
        }

        return $buttons;
    }
}
