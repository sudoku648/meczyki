<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Frontend\DataTable\Factory;

use Sudoku648\Meczyki\Shared\Frontend\DataTable\DataTableParams;
use Symfony\Component\HttpFoundation\Request;

final class DataTableParamsFactory
{
    public static function fromRequest(Request $request): DataTableParams
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

        return new DataTableParams(
            (int) $all['draw'],
            $orders,
            $searches,
            (int) $all['length'],
            $all['search']['value'],
            (int) (($all['start'] + $all['length']) / $all['length']),
        );
    }
}
