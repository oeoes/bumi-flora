<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Model\Storage\StorageRecord;
use App\Model\Storage\Stock;

class RecordItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return view('pages.persediaan.item-keluar-masuk')->with('records', self::items_record_query()->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.persediaan.record-item')->with('items', self::items_balance_query()->orderBy('balances.item_id')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $stock = Stock::where(['item_id' => $request->item_id, 'dept' => $request->dept])->first();
        
        // update stock
        if ($request->type == 'in') {
            StorageRecord::create([
                'item_id' => $request->item_id,
                'dept' => $request->dept,
                'transaction_no' => $request->transaction_no,
                'amount_in' => $request->amount,
                'description' => $request->description,
            ]);
            $stock->update([
                'amount' => $stock->amount + $request->amount
            ]);
        } else {
            StorageRecord::create([
                'item_id' => $request->item_id,
                'dept' => $request->dept,
                'transaction_no' => $request->transaction_no,
                'amount_out' => $request->amount,
                'description' => $request->description,
            ]);
            $stock->update([
                'amount' => $stock->amount - $request->amount
            ]);
        }

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($record)
    {
        return view('pages.persediaan.record-item-create')->with('item', self::items_balance_query()->where('balances.id', $record)->first());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public static function items_balance_query () {
        return DB::table('balances')
                ->join('items', 'items.id', '=', 'balances.item_id')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->join('brands', 'brands.id', '=', 'items.brand_id')
                ->select('balances.amount', 'balances.id as balance_id', 'balances.dept', 'items.*', 'units.unit', 'categories.category', 'brands.brand');
    }

    public function item_masuk () {
        $items = DB::table('items')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('storage_records', 'items.id', '=', 'storage_records.item_id')
                ->select('storage_records.*', 'items.name', 'items.price', 'units.unit')
                ->where('storage_records.amount_in', '!=', 'NULL')->get();
        return view('pages.persediaan.item-masuk')->with('items', $items);
    }

    public function item_keluar () {
        $items = DB::table('items')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('storage_records', 'items.id', '=', 'storage_records.item_id')
                ->select('storage_records.*', 'items.name', 'items.price', 'units.unit')
                ->where('storage_records.amount_out', '!=', 'NULL')->get();

        return view('pages.persediaan.item-keluar')->with('items', $items);
    }

    public function transfer_item (Request $request) {
        $from = Stock::where(['item_id' => $request->item_id, 'dept' => $request->from])->first();
        $to = Stock::where(['item_id' => $request->item_id, 'dept' => $request->to])->first();

        $from->update(['amount' => $from->amount - $request->amount]);
        $to->update(['amount' => $to->amount + $request->amount]);

        // record item masuk
        StorageRecord::create([
            'item_id' => $request->item_id,
            'dept' => $request->to,
            'transaction_no' => \Str::random(10),
            'amount_in' => $request->amount,
            'description' => 'Transfer item dari ' . $request->from,
        ]);

        // record item keluar
        StorageRecord::create([
            'item_id' => $request->item_id,
            'dept' => $request->from,
            'transaction_no' => \Str::random(10),
            'amount_out' => $request->amount,
            'description' => 'Transfer item ke penyimpanan ' . $request->to,
        ]);
        
        return back();
    }
}
