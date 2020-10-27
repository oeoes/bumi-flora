<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Activity\Transaction;
use Carbon\Carbon;

class CashierController extends Controller
{
    public function check_item (Request $request) {
        $item = DB::table('items')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->select('items.id', 'items.name', 'items.barcode', 'items.price', 'units.unit')
                ->where('items.barcode', $request->code)->get();

        if (count($item) < 1)
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan.']);
        return response()->json(['status' => true, 'message' => 'Item', 'data' => $item[0]]);
    }

    public function store_transaction (Request $request) {
        $time = Carbon::now()->format('H:i:s');

        foreach ($request->items as $item) {
            Transaction::create([
                'user_id' => auth()->user()->id,
                'item_id' => $item[0],
                'qty' => $item[1],
                'payment_method' => $request->payment_method,
                'payment_type' => $request->payment_type,
                'discount' => $request->discount,
                'transaction_time' => $time
            ]);
        }

        return response()->json(['status' => true, 'message' => 'Transaction recorded']);
    }

    public function cashier_history () {
        $items = DB::table('transactions')
                ->join('items', 'items.id', '=', 'transactions.item_id')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->join('brands', 'brands.id', '=', 'items.brand_id')
                ->join('balances', 'balances.item_id', '=', 'items.id')
                ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))
                ->where(['balances.dept' => 'utama', 'transactions.user_id' => auth()->user()->id])
                ->orderBy('transactions.payment_type', 'desc')
                ->select('items.*', 'transactions.qty', 'transactions.payment_method', 'transactions.payment_type', 'transactions.discount', 'transactions.transaction_time', 'transactions.created_at', 'units.unit', 'categories.category', 'brands.brand')->get();

        return view('pages.activity.cashier-history')->with('items', $items);
    }
}
