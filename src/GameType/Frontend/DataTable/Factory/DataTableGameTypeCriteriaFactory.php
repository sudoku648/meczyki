<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Frontend\DataTable\Factory;

use Sudoku648\Meczyki\GameType\Infrastructure\Persistence\PageView\GameTypePageView;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\DataTableParams;

class DataTableGameTypeCriteriaFactory
{
    public const string SORT_NAME     = 'name';
    public const string SORT_DIR_ASC  = 'ASC';
    public const string SORT_DIR_DESC = 'DESC';

    public const string SORT_DEFAULT     = self::SORT_NAME;
    public const string SORT_DIR_DEFAULT = self::SORT_DIR_ASC;
    public const int PER_PAGE            = 10;

    public static function fromParams(DataTableParams $params): GameTypePageView
    {
        $criteria                = new GameTypePageView($params->page);
        $criteria->sortColumn    = $params->order['column'] ?? self::SORT_DEFAULT;
        $criteria->sortDirection = $params->order['dir'] ?? self::SORT_DIR_DEFAULT;
        $criteria->perPage       = $params->length;

        $criteria->globalSearch  = $params->search;

        $criteria->nameLike = $params->searches['name'];

        return $criteria;
    }
}
