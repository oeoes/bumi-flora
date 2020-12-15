<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Storage\Balance;
use App\Model\Storage\Stock;
use App\Model\MasterData\Category;
use App\Model\MasterData\Item;
use App\Exports\StockOpnameExport;
use Excel;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class StorageController extends Controller
{
    public function index()
    {
        return view('pages.persediaan.saldo-awal')->with(['items' => self::items_query('utama')->get(), 'dept' => 'utama']);
    }

    public function edit($storage)
    {
        $balance = DB::table('balances')
                ->join('items', 'items.id', '=', 'balances.item_id')
                ->select('items.name', 'items.code', 'balances.amount', 'balances.id')->where('balances.id', $storage)->first();
        return view('pages.persediaan.edit-saldo-awal')->with('balance', $balance);
    }

    public function update(Request $request, Balance $storage)
    {
        // update stock
        $stock = Stock::where(['item_id' => $storage->item_id, 'dept' => $storage->dept])->first();
        $amount = $stock->amount - $storage->amount;
        $final_amount = $amount + $request->amount;
        $stock->update([
            'amount' => $final_amount
        ]);
        
        // update storage
        $storage->update([
            'amount' => $request->amount
        ]);

        return back();
    }

    public function filter_by_dept ($dept) {
        return view('pages.persediaan.saldo-awal')->with(['items' => self::items_query($dept)->get(), 'dept' => $dept]);
    }

    public function storage_utama () {
        $categories = Category::all();
        $items = self::items_query('utama')
                ->paginate()
                ->appends(request()->query());
        return view('pages.persediaan.penyimpanan.utama')->with(['items' => $items, 'categories' => $categories]);
    }

    public function storage_gudang () {
        $categories = Category::all();
        $items = self::items_query('gudang')
                ->paginate()
                ->appends(request()->query());
        return view('pages.persediaan.penyimpanan.gudang')->with(['items' => $items, 'categories' => $categories]);
    }

    public function storage_ecommerce () {
        return view('pages.persediaan.penyimpanan.ecommerce')->with('items', self::items_query('ecommerce')->get());
    }

    public function stock_opname () {
        $category = Category::select('id', 'category')->get();
        $cabs = Item::select('cabinet')->distinct()->get();

        return view('pages.persediaan.opname')->with(['categories' => $category, 'cabinet' => $cabs]);
    }

    public function export_opname () {
        Excel::store(new StockOpnameExport(request()), 'export.xlsx');
        return response()->download(storage_path().'/app/export.xlsx', 'data-opname-eport.xlsx', ['Content-Type' => ' application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])->deleteFileAfterSend();
    }

    public function filter_opname () {
        $opname = DB::table('items')
                ->leftJoin('storage_records', 'items.id', '=', 'storage_records.item_id')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->join('balances', 'items.id', '=', 'balances.item_id')
                ->join('stocks', 'items.id', '=', 'stocks.item_id')
                ->select('items.*', DB::raw('sum(storage_records.amount_in) as amount_in'), DB::raw('sum(storage_records.amount_out) as amount_out'), 'units.unit', 'categories.category', 'balances.amount as balance', 'balances.dept', 'stocks.amount as stock')
                ->where(['items.cabinet' => request('cabinet'), 'categories.id' => request('category'), 'storage_records.dept' => request('dept'), 'stocks.dept' => request('dept'), 'balances.dept' => request('dept')])
                ->whereBetween('items.created_at', [request('from'), request('to')])
                ->groupBy('storage_records.item_id')
                ->get();
        return response()->json(['status' => true, 'data' => $opname]);
    }

    public static function items_query ($dept) {
        return QueryBuilder::for(Balance::class)
                ->join('items', 'items.id', '=', 'balances.item_id')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->join('stocks', 'stocks.item_id', '=', 'items.id')
                ->where('balances.dept', $dept)->where('stocks.dept', $dept)
                ->select('balances.id as balance_id', 'balances.amount', 'balances.dept', 'items.*', 'units.unit', 'categories.category', 'stocks.amount as stock')
                ->orderBy('items.name')
                ->allowedFilters([
                    AllowedFilter::partial('items.name'),
                    AllowedFilter::partial('items.barcode'),
                    AllowedFilter::exact('categories.id'),
                ]);
    }
}
