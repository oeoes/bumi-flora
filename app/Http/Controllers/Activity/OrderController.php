<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\MasterData\Item;
use App\Model\Relation\StakeHolder;
use App\Model\Activity\Order;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = DB::table('items')
                    ->join('orders', 'items.id', '=', 'orders.item_id')
                    ->join('units', 'items.unit_id', '=', 'units.id')
                    ->join('stocks', 'items.id', '=', 'stocks.item_id')
                    ->join('stake_holders', 'orders.stake_holder_id', '=', 'stake_holders.id')
                    ->select('orders.*', 'items.name', 'items.price', 'units.unit', 'stake_holders.name as supplier_name', 'stake_holders.address', 'stocks.dept')
                    ->get();
        return view('pages.activity.index')->with('orders', $orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Order::create($request->all());
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Item::find($id);
        $suppliers = StakeHolder::where('type', 'supplier')->get();
        return view('pages.activity.pesanan-pembelian')->with(['item' => $item, 'suppliers' => $suppliers]);
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
}
