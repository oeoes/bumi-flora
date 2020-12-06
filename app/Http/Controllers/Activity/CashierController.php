<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Activity\Transaction;
use App\Model\MasterData\PaymentType;
use App\Model\MasterData\Item;
use App\Model\Storage\StorageRecord;
use App\Model\Storage\Stock;
use Carbon\Carbon;

// print receipt
use App\Http\Controllers\Activity\PrintReceiptController;

class CashierController extends Controller
{
    public function check_item (Request $request) {
        $item = DB::table('items')
                ->join('stocks', 'items.id', '=', 'stocks.item_id')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->leftJoin('discounts', 'categories.id', '=', 'discounts.category_id')
                ->where('stocks.dept', $request->dept)
                ->select('items.id', 'stocks.amount as stock', 'items.name', 'items.barcode', 'units.unit', 'items.price as original_price', DB::raw('IFNULL(discounts.value, 0) * CAST(discounts.status as UNSIGNED) as discount'), DB::raw('items.price - ((items.price * IFNULL(discounts.value, 0) / 100) * CAST(discounts.status as UNSIGNED)) as price'))
                ->where('items.barcode', $request->code)->get();

        if (count($item) < 1)
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan.']);
        return response()->json(['status' => true, 'message' => 'Item', 'data' => $item[0]]);
    }

    public function store_transaction (Request $request) {
        $time = Carbon::now()->format('H:i:s');
        $payment_type = PaymentType::find($request->payment_type);
        $no_urut = Transaction::whereDate('created_at', Carbon::now()->format('Y-m-d'))->groupBy('transaction_time')->get();

        // load data to transmit to printer
        $print_items = [];
        $total_price = 0;
        $bill = $total_price + $request->tax + $request->additional_fee;

        $calc = [
            "total_price" => $total_price,
            "fee" => $request->additional_fee,
            "tax" => $request->tax,
            "bill" => $bill,
            "cash" => $request->payment_type != 1 ? $payment_type->type_name : $request->nominal,
            "cashback" => $request->payment_type != 1 ? '-' : $request->nominal - $bill,
        ];

        foreach ($request->items as $item) {
            $stock = Stock::where(['item_id' => $item[0], 'dept' => $request->dept])->first();
            $trx_number = (count($no_urut)+1) . '/KSR/' . strtoupper($request->dept);
            // count price of all items
            $price = Item::find($item[0]);
            $total_price += $price->price;

            Transaction::create([
                'user_id' => auth()->user()->id,
                'item_id' => $item[0],
                'stake_holder_id' => $request->customer,
                'transaction_number' => $trx_number . '/' . Carbon::now()->format('Y-m-d'),
                'qty' => $item[1],
                'dept' => $request->dept,
                'payment_method_id' => $payment_type->payment_method_id,
                'payment_type_id' => $payment_type->id,
                'discount' => $request->discount,
                'additional_fee' => $request->additional_fee,
                'tax' => $request->tax,
                'transaction_time' => $time
            ]);

            // record item keluar
            StorageRecord::create([
                'item_id' => $item[0],
                'dept' => $request->dept,
                'transaction_no' => (count($no_urut)+1) . '/KSR/'. strtoupper($request->dept). '/' . Carbon::now()->format('Y-m-d'),
                'amount_out' => $item[1],
                'description' => $request->dept == 'utama' ? 'Penjualan offline' : 'Penjualan Online',
            ]);
            $stock->update([
                'amount' => $stock->amount - $item[1]
            ]);

            // array data receipt
            $data_item = [
                "name" => $item[2],
                "satuan" => $item[3],
                "price" => $item[4],
                "qty" => $item[1],
                "total" => number_format((integer) $item[4] * (integer) $item[1]),
                "discount" => $item[5],
                "transaction_number" => $trx_number
            ];
            // push ke array load data receipt
            array_push($print_items, $data_item);
            // print receipt
            PrintReceiptController::print_receipt($print_items, $calc);
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
