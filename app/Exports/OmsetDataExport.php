<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class OmsetDataExport implements FromView
{
    private $dept, $date_from, $date_to, $omset_type, $item, $category;
    public function __construct ($dept, $date_from, $date_to, $omset_type, $item, $category) {
        $this->dept = $dept;
        $this->date_from = $date_from;
        $this->date_to = $date_to;
        $this->omset_type = $omset_type;
        $this->item = $item;
        $this->category = $category;
    }
    /**
    * @return \Illuminate\Support\View
    */
    public function view(): View
    {
        $tot_omset = 0;
        $tot_profit = 0;

        $query = DB::table('items')
                ->join('transactions', 'items.id', '=', 'transactions.item_id')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->select('items.id', 'items.name', 'units.unit', 'categories.category', 'items.main_cost', 'items.price', DB::raw('sum(transactions.qty) as qty'), DB::raw('sum(transactions.discount) as discount'), DB::raw('sum(transactions.discount_item) as discount_item'), DB::raw('sum(transactions.discount_customer) as discount_customer'), DB::raw('((sum(transactions.qty) * items.price) - (sum(transactions.discount) + sum(transactions.discount_item) + sum(transactions.discount_customer))) as omset'), DB::raw('(((sum(transactions.qty) * items.price) - (sum(transactions.discount) + sum(transactions.discount_item) + sum(transactions.discount_customer))) - (sum(transactions.qty) * items.main_cost)) as profit'))
                ->groupBy('transactions.item_id')
                ->where('transactions.dept', $this->dept)
                ->whereBetween('transactions.created_at', [$this->date_from, $this->date_to]);

        $omset = self::omset_query($query, $this->omset_type, $this->item, $this->category)->get();

        foreach ($omset as $om) {
            $tot_omset += $om->omset;
            $tot_profit += $om->profit;
        }

        return view('exports.omset', ['omset' => $omset, 'total_omset' => $tot_omset, 'total_profit' => $tot_profit, 'date_from' => $this->date_from, 'date_to' => $this->date_to]);
    }

    public static function omset_query ($query, $omset_type, $item, $category) {
        if($omset_type == 'item') {
            return $query->where('items.id', $item);
        } else if ($omset_type == 'category') {
            return $query->where('categories.id', $category);
        } else {
            return $query;
        }
    }
}
