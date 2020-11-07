<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Activity\Transaction;
use App\Model\MasterData\PaymentType;
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
        $payment_type = PaymentType::find($request->payment_type);
        $no_urut = Transaction::whereDate('created_at', Carbon::now()->format('Y-m-d'))->groupBy('transaction_time')->count();

        foreach ($request->items as $item) {
            Transaction::create([
                'user_id' => auth()->user()->id,
                'item_id' => $item[0],
                'transaction_number' => ($no_urut+1) . '/KSR/' . Carbon::now()->format('Y-m-d'),
                'qty' => $item[1],
                'payment_method_id' => $payment_type->payment_method_id,
                'payment_type_id' => $payment_type->id,
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
                ->join('payment_types', 'payment_types.id', '=', 'transactions.payment_type_id')
                ->join('payment_methods', 'payment_methods.id', '=', 'transactions.payment_method_id')
                ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))
                ->where(['balances.dept' => 'utama', 'transactions.user_id' => auth()->user()->id])
                ->select('items.*', 'transactions.transaction_number', 'transactions.qty', 'payment_methods.method_name', 'payment_types.type_name', 'transactions.discount', 'transactions.transaction_time', 'transactions.created_at', 'units.unit', 'categories.category', 'brands.brand')->get();

        return view('pages.activity.cashier-history')->with('items', $items);
    }
}
