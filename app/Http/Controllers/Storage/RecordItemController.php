<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Model\Storage\ItemRecord;
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
        return view('pages.persediaan.item-keluar-masuk')->with('records', self::items_record_query()->get());
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

        ItemRecord::create([
            'item_id' => $request->item_id,
            'dept' => $request->dept,
            'transaction_no' => $request->transaction_no,
            'type' => $request->type,
            'date' => \Carbon\Carbon::now(),
            'amount' => $request->amount,
            'description' => $request->description,
        ]);
        
        // update stock
        if ($request->type == 'in') {
            $stock->update([
                'amount' => $stock->amount + $request->amount
            ]);
        } else {
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

    public static function items_record_query () {
        return DB::table('item_records')
                ->join('items', 'items.id', '=', 'item_records.item_id')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->select('item_records.*', 'items.name', 'items.code', 'items.price', 'units.unit');
    }

    public function item_masuk () {
        return view('pages.persediaan.item-masuk')->with('items', self::items_record_query()->where('item_records.type', 'in')->get());
    }

    public function item_keluar () {
        return view('pages.persediaan.item-keluar')->with('items', self::items_record_query()->where('item_records.type', 'out')->get());
    }
}
