<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Frontend\DataTable\Factory;

use Sudoku648\Meczyki\Shared\Frontend\DataTable\DataTableParams;
use Sudoku648\Meczyki\Team\Infrastructure\Persistence\PageView\TeamPageView;

class DataTableTeamCriteriaFactory
{
    public const SORT_SHORT_NAME = 'shortName';
    public const SORT_CLUB_NAME  = 'club';
    public const SORT_DIR_ASC    = 'ASC';
    public const SORT_DIR_DESC   = 'DESC';

    public const SORT_DEFAULT     = self::SORT_SHORT_NAME;
    public const SORT_DIR_DEFAULT = self::SORT_DIR_ASC;
    public const PER_PAGE         = 10;

    public static function fromParams(DataTableParams $params): TeamPageView
    {
        $criteria                = new TeamPageView($params->page);
        $criteria->sortColumn    = $params->order['column'] ?? self::SORT_DEFAULT;
        $criteria->sortDirection = $params->order['dir'] ?? self::SORT_DIR_DEFAULT;
        $criteria->perPage       = $params->length;

        $criteria->globalSearch  = $params->search;

        $criteria->nameLike     = $params->searches['name'];
        $criteria->clubNameLike = $params->searches['club'];

        return $criteria;
    }
}
