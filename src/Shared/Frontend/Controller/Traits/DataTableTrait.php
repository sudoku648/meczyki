<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Frontend\Controller\Traits;

use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;
use Symfony\Component\HttpFoundation\Request;

trait DataTableTrait
{
    protected function prepareDataTableAjaxRequest(Request $request): array
    {
        $all = $request->request->all();

        $orders = [];
        foreach ($all['order'] as $order) {
            $orders['column'] = $all['columns'][$order['column']]['name'];
            $orders['dir']    = $order['dir'];
        }

        $searches = [];
        foreach ($all['columns'] as $column) {
            $searches[$column['name']] = $column['search']['value'];
        }

        return [
            'draw'     => (int) $all['draw'],
            'order'    => $orders,
            'searches' => $searches,
            'length'   => $all['length'],
            'search'   => $all['search']['value'],
            'page'     => ($all['start'] + $all['length']) / $all['length'],
        ];
    }

    protected function getOrdinalNumberForDataTable(int $key, Criteria $criteria): int
    {
        return $key + 1 + ($criteria->page - 1) * $criteria->perPage;
    }
}
