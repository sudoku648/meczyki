<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Frontend\DataTable\Factory;

use Symfony\Component\HttpFoundation\Request;

final class DataTableParamsFactory
{
    public static function fromRequest(Request $request): array
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
}
