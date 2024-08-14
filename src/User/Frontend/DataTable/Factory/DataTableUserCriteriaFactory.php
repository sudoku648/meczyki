<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\DataTable\Factory;

use Sudoku648\Meczyki\Shared\Frontend\DataTable\DataTableParams;
use Sudoku648\Meczyki\User\Infrastructure\Persistence\PageView\UserPageView;

class DataTableUserCriteriaFactory
{
    public const string SORT_USERNAME = 'username';
    public const string SORT_DIR_ASC  = 'ASC';
    public const string SORT_DIR_DESC = 'DESC';

    public const string SORT_DEFAULT     = self::SORT_USERNAME;
    public const string SORT_DIR_DEFAULT = self::SORT_DIR_ASC;
    public const int PER_PAGE            = 10;

    public static function fromParams(DataTableParams $params): UserPageView
    {
        $criteria                = new UserPageView($params->page);
        $criteria->sortColumn    = $params->order['column'] ?? self::SORT_DEFAULT;
        $criteria->sortDirection = $params->order['dir'] ?? self::SORT_DIR_DEFAULT;
        $criteria->perPage       = $params->length;

        $criteria->globalSearch  = $params->search;

        $criteria->usernameLike = $params->searches['username'];

        return $criteria;
    }
}
