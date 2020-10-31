<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\OmsetDataExport;
use Excel;

class OmsetController extends Controller
{
    public function index() {
        return view('pages.admin.omset');
    }

    public function calculate_omset () {
        $omset = DB::table('items')
                ->join('transactions', 'items.id', '=', 'transactions.item_id')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->select('items.id', 'items.name', 'units.unit', 'categories.category', 'items.main_cost', 'items.price', DB::raw('sum(transactions.qty) as qty'), 'transactions.discount', DB::raw('((sum(transactions.qty) * items.price) - transactions.discount) as omset'), DB::raw('(((sum(transactions.qty) * items.price) - transactions.discount) - (sum(transactions.qty) * items.main_cost)) as profit'))
                ->groupBy('transactions.item_id')
                ->whereBetween('transactions.created_at', [request('date_from'), request('date_to')])
                ->get();
        return response()->json(['status' => true, 'data' => $omset]);
    }

    public function export_omset () {
        Excel::store(new OmsetDataExport(request('date_from'), request('date_to')), 'omset_export.xlsx');
        return response()->download(storage_path().'/app/omset_export.xlsx', 'data-omset-export.xlsx', ['Content-Type' => ' application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])->deleteFileAfterSend();
    }
}
