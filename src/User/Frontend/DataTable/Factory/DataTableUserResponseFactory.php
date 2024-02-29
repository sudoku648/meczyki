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
                'buttons/show.html.twig',
                [
                    'btn_size'   => 'table',
                    'path'       => 'user_single',
                    'parameters' => [
                        'user_id' => $user->getId(),
                    ],
                ]
            );
        }
        if ($this->security->isGranted(UserVoter::EDIT, $user)) {
            $buttons .= $this->twig->render(
                'buttons/edit.html.twig',
                [
                    'btn_size'   => 'table',
                    'path'       => 'user_edit',
                    'parameters' => [
                        'user_id' => $user->getId(),
                    ],
                ]
            );
        }
        if ($this->security->isGranted(UserVoter::ACTIVATE, $user)) {
            $buttons .= $this->twig->render(
                'user/_activate_form.html.twig',
                [
                    'btn_size' => 'table',
                    'user'     => $user,
                ]
            );
        } elseif ($this->security->isGranted(UserVoter::DEACTIVATE, $user)) {
            $buttons .= $this->twig->render(
                'user/_deactivate_form.html.twig',
                [
                    'btn_size' => 'table',
                    'user'     => $user,
                ]
            );
        }
        if ($this->security->isGranted(UserVoter::BIND_WITH_PERSON, $user)) {
            $buttons .= $this->twig->render(
                'user/_buttons/bind_with_person.html.twig',
                [
                    'btn_size'   => 'table',
                    'path'       => 'user_bind_with_person',
                    'parameters' => [
                        'user_id' => $user->getId(),
                    ],
                ]
            );
        }
        if ($this->security->isGranted(UserVoter::IMPERSONATE, $user)) {
            $buttons .= $this->twig->render(
                'user/_buttons/impersonate.html.twig',
                [
                    'btn_size'   => 'table',
                    'path'       => 'dashboard',
                    'parameters' => [
                        '_switch_user' => $user->getUserIdentifier(),
                    ],
                ]
            );
        }
        if ($this->security->isGranted(UserVoter::DELETE, $user)) {
            $buttons .= $this->twig->render(
                'user/_delete_form.html.twig',
                [
                    'btn_size' => 'table',
                    'user'     => $user,
                ]
            );
        }

        return $buttons;
    }
}
