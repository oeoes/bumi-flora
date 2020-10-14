<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Storage\Balance;
use App\Model\Storage\Stock;

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
        return view('pages.persediaan.penyimpanan.utama')->with('items', self::items_query('utama')->get());
    }

    public function storage_gudang () {
        return view('pages.persediaan.penyimpanan.gudang')->with('items', self::items_query('gudang')->get());
    }

    public function stock_opname () {
        return $query = DB::table('items')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->join('balances', 'items.id', '=', 'balances.item_id')
                ->join('item_ins', 'items.id', '=', 'item_ins.item_id')
                ->join('item_outs', 'items.id', '=', 'item_outs.item_id')
                ->join('stocks', 'items.id', '=', 'stocks.item_id')
                ->select('items.*', 'units.unit', 'item_ins.*', 'categories.category', 'balances.amount as balance', 'balances.dept', 'stocks.amount as stock')
                ->where(['balances.dept' => 'utama', 'stocks.dept' => 'utama'])->get();
                
        return view('pages.persediaan.opname')->with('opname', $query);
    }

    public static function items_query ($dept) {
        return DB::table('balances')
                ->join('items', 'items.id', '=', 'balances.item_id')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->join('stocks', 'stocks.item_id', '=', 'items.id')
                ->where('balances.dept', $dept)->where('stocks.dept', $dept)
                ->select('balances.id as balance_id', 'balances.amount', 'balances.dept', 'items.*', 'units.unit', 'categories.category', 'stocks.amount as stock');
    }
}
