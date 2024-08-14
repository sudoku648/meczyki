<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\DataTable\Factory;

use Sudoku648\Meczyki\Person\Infrastructure\Persistence\PageView\PersonPageView;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\DataTableParams;

class DataTablePersonCriteriaFactory
{
    public const string SORT_FULLNAME = 'fullName';
    public const string SORT_DIR_ASC  = 'ASC';
    public const string SORT_DIR_DESC = 'DESC';

    public const string SORT_DEFAULT     = self::SORT_FULLNAME;
    public const string SORT_DIR_DEFAULT = self::SORT_DIR_ASC;
    public const int PER_PAGE            = 10;

    public static function fromParams(DataTableParams $params): PersonPageView
    {
        $criteria                = new PersonPageView($params->page);
        $criteria->sortColumn    = $params->order['column'] ?? self::SORT_DEFAULT;
        $criteria->sortDirection = $params->order['dir'] ?? self::SORT_DIR_DEFAULT;
        $criteria->perPage       = $params->length;

        $criteria->globalSearch  = $params->search;

        $criteria->fullNameLike = $params->searches['fullName'];

        return $criteria;
    }
}
