<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class OmsetDataExport implements FromView
{
    private $date_from, $date_to;
    public function __construct ($date_from, $date_to) {
        $this->date_from = $date_from;
        $this->date_to = $date_to;
    }
    /**
    * @return \Illuminate\Support\View
    */
    public function view(): View
    {
        $tot_omset = 0;
        $tot_profit = 0;

        $omset = DB::table('items')
                ->join('transactions', 'items.id', '=', 'transactions.item_id')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->select('items.id', 'items.name', 'units.unit', 'categories.category', 'items.main_cost', 'items.price', DB::raw('sum(transactions.qty) as qty'), 'transactions.discount', DB::raw('((sum(transactions.qty) * items.price) - transactions.discount) as omset'), DB::raw('(((sum(transactions.qty) * items.price) - transactions.discount) - (sum(transactions.qty) * items.main_cost)) as profit'))
                ->groupBy('transactions.item_id')
                ->whereBetween('transactions.created_at', [$this->date_from, $this->date_to])
                ->get();

        foreach ($omset as $om) {
            $tot_omset += $om->omset;
            $tot_profit += $om->profit;
        }

        return view('exports.omset', ['omset' => $omset, 'total_omset' => $tot_omset, 'total_profit' => $tot_profit, 'date_from' => $this->date_from, 'date_to' => $this->date_to]);
    }
}
