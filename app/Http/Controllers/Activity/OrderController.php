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
        $orders = DB::table('orders')
                    ->join('items', 'items.id', '=', 'orders.item_id')
                    ->join('units', 'items.unit_id', '=', 'units.id')
                    ->join('stocks', 'items.id', '=', 'stocks.item_id')
                    ->join('stake_holders', 'orders.stake_holder_id', '=', 'stake_holders.id')
                    ->select('orders.*', 'items.name', 'items.price', 'units.unit', 'stake_holders.name as supplier_name', 'stake_holders.address', 'stocks.dept')
                    ->where('stocks.dept', 'gudang')->where('orders.status', 0)
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

        session()->flash('message', 'Yeay! Pesanan berhasil dibuat.');
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

    public function history_order () {
        $orders = DB::table('orders')
                    ->join('items', 'items.id', '=', 'orders.item_id')
                    ->join('units', 'items.unit_id', '=', 'units.id')
                    ->join('stocks', 'items.id', '=', 'stocks.item_id')
                    ->join('stake_holders', 'orders.stake_holder_id', '=', 'stake_holders.id')
                    ->select('orders.*', 'items.name', 'items.price', 'units.unit', 'stake_holders.name as supplier_name', 'stake_holders.address', 'stocks.dept')
                    ->where('stocks.dept', 'gudang')->where('orders.status', 1)
                    ->get();
        return view('pages.activity.history')->with('orders', $orders);
    }

    public function accept_item (Request $request, Order $order) {
        if (($order->accepted + $request->amount) >= $order->amount) {
            $order->update([
                'accepted' => $order->accepted + $request->amount,
                'status' => 1
            ]);
        }
        else {
            $order->update([
                'accepted' => $order->accepted + $request->amount,
            ]);
        }

        return back();
    }

    public function return_item (Request $request, Order $order) {
        if ($request->amount != 0 && $request->amount <= $order->amount) {
            $order->update([
                'accepted' => $order->accepted - $request->amount,
                'status' => 0
            ]);
            return back();
        }
        else {
            return back(); // with flashed data
        }
    }

    public function cashier_page () {
        $items = DB::table('items')
                ->join('stocks', 'items.id', '=', 'stocks.item_id')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->leftJoin('discounts', 'categories.id', '=', 'discounts.category_id')
                ->where('stocks.dept', 'utama')
                ->select('items.id', 'items.name', 'items.barcode', 'units.unit', 'items.price as original_price', DB::raw('IFNULL(discounts.value, 0)as discount'), DB::raw('items.price - (items.price * IFNULL(discounts.value, 0) / 100) as price'))->get();
        $customer = StakeHolder::where('type', 'customer')->get();
        $payment_method = DB::table('payment_methods')->select('id', 'method_name')->get();

        return view('pages.activity.cashier')->with(['items' => $items, 'payment_method' => $payment_method, 'customers' => $customer]);
    }
}
