<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\OmsetDataExport;
use App\Model\MasterData\Category;
use Excel;

class OmsetController extends Controller
{
    public function index() {
        $categories = Category::all();
        $items = DB::table('items')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->join('brands', 'brands.id', '=', 'items.brand_id')
                ->join('balances', 'balances.item_id', '=', 'items.id')
                ->where('balances.dept', 'utama')
                ->select('items.name', 'items.id', 'items.price', 'items.main_cost', 'units.unit')->get();

        return view('pages.admin.omset')->with(['categories' => $categories, 'items' => $items]);
    }

    public function calculate_omset (Request $request) {
        $query = DB::table('items')
                ->join('transactions', 'items.id', '=', 'transactions.item_id')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->select('items.id', 'items.name', 'units.unit', 'categories.category', 'items.main_cost', 'items.price', DB::raw('sum(transactions.qty) as qty'), 'transactions.discount', DB::raw('((sum(transactions.qty) * items.price) - transactions.discount) as omset'), DB::raw('(((sum(transactions.qty) * items.price) - transactions.discount) - (sum(transactions.qty) * items.main_cost)) as profit'))
                ->groupBy('transactions.item_id')
                ->where('transactions.dept', $request->dept)
                ->whereBetween('transactions.created_at', [$request->date_from, $request->date_to]);
        $omset = self::omset_query($query, $request)->get();
        return response()->json(['status' => true, 'data' => $omset]);
    }

    public static function omset_query ($query, $req) {
        if($req->omset_type == 'item') {
            return $query->where('items.id', $req->item);
        } else if ($req->omset_type == 'category') {
            return $query->where('categories.id', $req->category);
        } else {
            return $query;
        }
    }

    public function export_omset () {
        Excel::store(new OmsetDataExport(request('dept'), request('date_from'), request('date_to'), request('omset_type'), request('item'), request('category')), 'omset_export.xlsx');
        return response()->download(storage_path().'/app/omset_export.xlsx', 'data-omset-export.xlsx', ['Content-Type' => ' application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])->deleteFileAfterSend();
    }
}
