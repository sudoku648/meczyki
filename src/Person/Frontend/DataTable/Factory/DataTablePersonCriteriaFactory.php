<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\DataTable\Factory;

use Sudoku648\Meczyki\Person\Infrastructure\Persistence\PageView\PersonPageView;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\DataTableParams;

class DataTablePersonCriteriaFactory
{
    public const SORT_FULLNAME = 'fullName';
    public const SORT_DIR_ASC  = 'ASC';
    public const SORT_DIR_DESC = 'DESC';

    public const SORT_DEFAULT     = self::SORT_FULLNAME;
    public const SORT_DIR_DEFAULT = self::SORT_DIR_ASC;
    public const PER_PAGE         = 10;

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
