<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Frontend\DataTable\Factory;

use Sudoku648\Meczyki\MatchGame\Infrastructure\Persistence\PageView\MatchGamePageView;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\DataTableParams;

class DataTableMatchGameCriteriaFactory
{
    public const string SORT_DATETIME = 'dateTime';
    public const string SORT_DIR_ASC  = 'ASC';
    public const string SORT_DIR_DESC = 'DESC';

    public const string SORT_DEFAULT     = self::SORT_DATETIME;
    public const string SORT_DIR_DEFAULT = self::SORT_DIR_DESC;
    public const int PER_PAGE            = 10;

    public static function fromParams(DataTableParams $params): MatchGamePageView
    {
        $criteria                = new MatchGamePageView($params->page);
        $criteria->sortColumn    = $params->order['column'] ?? self::SORT_DEFAULT;
        $criteria->sortDirection = $params->order['dir'] ?? self::SORT_DIR_DEFAULT;
        $criteria->perPage       = $params->length;

        $criteria->globalSearch  = $params->search;

        $criteria->dateTimeLike = $params->searches['dateTime'];
        $criteria->gameTypeLike = $params->searches['gameType'];
        $criteria->teamsLike    = $params->searches['teams'];

        return $criteria;
    }
}
