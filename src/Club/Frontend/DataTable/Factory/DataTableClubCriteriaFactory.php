<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Frontend\DataTable\Factory;

use Sudoku648\Meczyki\Club\Infrastructure\Persistence\PageView\ClubPageView;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\DataTableParams;

class DataTableClubCriteriaFactory
{
    public const SORT_NAME     = 'name';
    public const SORT_DIR_ASC  = 'ASC';
    public const SORT_DIR_DESC = 'DESC';

    public const SORT_DEFAULT     = self::SORT_NAME;
    public const SORT_DIR_DEFAULT = self::SORT_DIR_ASC;
    public const PER_PAGE         = 10;

    public static function fromParams(DataTableParams $params): ClubPageView
    {
        $criteria                = new ClubPageView($params->page);
        $criteria->sortColumn    = $params->order['column'] ?? self::SORT_DEFAULT;
        $criteria->sortDirection = $params->order['dir'] ?? self::SORT_DIR_DEFAULT;
        $criteria->perPage       = $params->length;

        $criteria->globalSearch  = $params->search;

        $criteria->nameLike = $params->searches['name'];

        return $criteria;
    }
}
