<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Activity\Transaction;
use App\Model\Activity\StockWarnNotification;
use App\Model\MasterData\PaymentType;
use App\Model\MasterData\Item;
use App\Model\Storage\StorageRecord;
use App\Model\Storage\Stock;
use Carbon\Carbon;
use App\Model\Relation\StakeHolder;

// print receipt
use App\Http\Controllers\Activity\PrintReceiptController;
use App\Http\Controllers\Activity\PrintDailyReportReceiptController as PDR;

class CashierController extends Controller
{
    public function __construct () {
        $this->middleware(['role:super_admin|root|cashier']);
    }

    public function index () {
        $items = DB::table('items')
                ->join('stocks', 'items.id', '=', 'stocks.item_id')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->leftJoin('grosir_items', 'items.id', '=', 'grosir_items.item_id')
                ->leftJoin('discounts as discount_categories', 'categories.id', '=', 'discount_categories.category_id')
                ->leftJoin('discounts as discount_items', 'items.id', '=', 'discount_items.item_id')
                ->leftJoin('discount_periodes as discount_periode_item', 'discount_items.id', '=', 'discount_periode_item.discount_id')
                ->leftJoin('discount_periodes as discount_periode_category', 'discount_categories.id', '=', 'discount_periode_category.discount_id')
                ->where('stocks.dept', 'utama')
                ->select('items.id', 'stocks.amount as stock', 'items.name', 'items.barcode', 'units.unit', 'items.price as original_price', DB::raw('IFNULL(discount_items.value * CAST(discount_items.status as UNSIGNED), 0) as discount_item'), DB::raw('IFNULL(discount_categories.value * CAST(discount_categories.status as UNSIGNED), 0) as discount_category'), DB::raw('items.price - (IFNULL((items.price * discount_categories.value / 100) * CAST(discount_categories.status as UNSIGNED), 0)) as price_category'), DB::raw('items.price - (IFNULL((items.price * discount_items.value / 100) * CAST(discount_items.status as UNSIGNED), 0)) as price_item'), 'discount_periode_category.occurences as category_occurences', 'discount_periode_item.occurences as item_occurences', DB::raw('IFNULL(grosir_items.minimum_item, 0) as minimum_item'), 'grosir_items.price as grosir_price')->get();
        $customer = StakeHolder::where('type', 'customer')->distinct()->get();
        $payment_method = DB::table('payment_methods')->select('id', 'method_name')->get();

        return view('pages.activity.cashier')->with(['items' => $items, 'payment_method' => $payment_method, 'customers' => $customer]);
    }

    public function cashier_ecommerce () {
        $items = DB::table('items')
                ->join('stocks', 'items.id', '=', 'stocks.item_id')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->leftJoin('discounts as discount_categories', 'categories.id', '=', 'discount_categories.category_id')
                ->leftJoin('discounts as discount_items', 'items.id', '=', 'discount_items.item_id')
                ->leftJoin('discount_periodes as discount_periode_item', 'discount_items.id', '=', 'discount_periode_item.discount_id')
                ->leftJoin('discount_periodes as discount_periode_category', 'discount_categories.id', '=', 'discount_periode_category.discount_id')
                ->where('stocks.dept', 'ecommerce')
                ->select('items.id', 'stocks.amount as stock', 'items.name', 'items.barcode', 'units.unit', 'items.price as original_price', DB::raw('IFNULL(discount_items.value * CAST(discount_items.status as UNSIGNED), 0) as discount_item'), DB::raw('IFNULL(discount_categories.value * CAST(discount_categories.status as UNSIGNED), 0) as discount_category'), DB::raw('items.price - (IFNULL((items.price * discount_categories.value / 100) * CAST(discount_categories.status as UNSIGNED), 0)) as price_category'), DB::raw('items.price - (IFNULL((items.price * discount_items.value / 100) * CAST(discount_items.status as UNSIGNED), 0)) as price_item'), 'discount_periode_category.occurences as category_occurences', 'discount_periode_item.occurences as item_occurences')->get();
        $customer = StakeHolder::where('type', 'customer')->distinct()->get();
        $payment_method = DB::table('payment_methods')->select('id', 'method_name')->get();

        return view('pages.activity.cashier-ecommerce')->with(['items' => $items, 'payment_method' => $payment_method, 'customers' => $customer]);
    }

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
            $master_item = Item::find($item[0]);
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
            
        }

        // warning if stock less than minimum stock or even zero left
        if ($stock->amount < $master_item->min_stock && $stock->amount > 0) {
            $title = $master_item->name . ' kurang dari stock minimum.';
            $link = route('items.show', ['item' => $master_item->id]);
            $body = 'Jumlah item untuk '. $master_item->name . ' kurang dari stock minimum. Segera lakukan restock sebelum kehabisan, untuk lebih detail klik tautan ini <a href="'.$link.'">detail.</a>';
            StockWarnNotification::create([
                'title' => $title,
                'body' => $body,
                'urgency' => 1,
            ]);
        } else if ($stock->amount == 0) {
            $title = $master_item->name . ' stock habis.';
            $link = route('items.show', ['item' => $master_item->id]);
            $body = 'Jumlah item untuk '. $master_item->name . ' tersisa 0. Segera lakukan restock, klik tautan ini untuk lebih detail <a href="'.$link.'">detail.</a>';
            StockWarnNotification::create([
                'title' => $title,
                'body' => $body,
                'urgency' => 2,
            ]);
        }

        try {
            // print receipt
            PrintReceiptController::print_receipt($print_items, $calc);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Printer error'], 400);
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

        return view('pages.activity.cashier-history')->with(['items' => $items, 'data' => self::daily_report_data()]);
    }

    public static function print_cashier_history () {
        PDR::print_daily_report_receipt(self::daily_report_data());
    }

    public static function daily_report_data () {
        $quantity = 0;
        $discount = 0;
        $tax = 0;
        $fee = 0;
        $total = 0;
        $cash = 0;
        $ewallet = 0;
        $debit = 0;
        $transfer = 0;
        $credit = 0;

        $items = DB::table('transactions')
                ->join('items', 'items.id', '=', 'transactions.item_id')
                ->join('payment_types', 'payment_types.id', '=', 'transactions.payment_type_id')
                ->join('payment_methods', 'payment_methods.id', '=', 'transactions.payment_method_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->where(['transactions.dept' => 'utama', 'transactions.user_id' => auth()->user()->id])
                ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))
                ->groupBy('transactions.transaction_number')
                ->select(DB::raw('sum(transactions.qty) as quantity'), 'transactions.id', 'transactions.dept', 'transactions.transaction_number', 'payment_methods.method_name', 'payment_types.type_name', 'transactions.transaction_time', 'transactions.created_at', 'items.price', 'items.id as item_id', 'items.name')
                ->get();

        $cashes = DB::table('transactions')
                ->join('items', 'items.id', '=', 'transactions.item_id')
                ->join('payment_types', 'payment_types.id', '=', 'transactions.payment_type_id')
                ->join('payment_methods', 'payment_methods.id', '=', 'transactions.payment_method_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->where(['transactions.dept' => 'utama', 'transactions.user_id' => auth()->user()->id, 'payment_methods.method_name' => 'cash'])
                ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))
                ->groupBy('payment_methods.id', 'items.id')
                ->select(DB::raw('sum(transactions.qty) as quantity'), 'items.price', 'payment_methods.id', 'payment_methods.method_name')
                ->get();
        
        $ewallets = DB::table('transactions')
                ->join('items', 'items.id', '=', 'transactions.item_id')
                ->join('payment_types', 'payment_types.id', '=', 'transactions.payment_type_id')
                ->join('payment_methods', 'payment_methods.id', '=', 'transactions.payment_method_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->where(['transactions.dept' => 'utama', 'transactions.user_id' => auth()->user()->id, 'payment_methods.method_name' => 'ewallet'])
                ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))
                ->groupBy('payment_methods.id', 'items.id')
                ->select(DB::raw('sum(transactions.qty) as quantity'), 'items.price', 'payment_methods.id', 'payment_methods.method_name')
                ->get();

        $debits = DB::table('transactions')
                ->join('items', 'items.id', '=', 'transactions.item_id')
                ->join('payment_types', 'payment_types.id', '=', 'transactions.payment_type_id')
                ->join('payment_methods', 'payment_methods.id', '=', 'transactions.payment_method_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->where(['transactions.dept' => 'utama', 'transactions.user_id' => auth()->user()->id, 'payment_methods.method_name' => 'debit'])
                ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))
                ->groupBy('payment_methods.id', 'items.id')
                ->select(DB::raw('sum(transactions.qty) as quantity'), 'items.price', 'payment_methods.id', 'payment_methods.method_name')
                ->get();

        $transfers = DB::table('transactions')
                ->join('items', 'items.id', '=', 'transactions.item_id')
                ->join('payment_types', 'payment_types.id', '=', 'transactions.payment_type_id')
                ->join('payment_methods', 'payment_methods.id', '=', 'transactions.payment_method_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->where(['transactions.dept' => 'utama', 'transactions.user_id' => auth()->user()->id, 'payment_methods.method_name' => 'transfer'])
                ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))
                ->groupBy('payment_methods.id', 'items.id')
                ->select(DB::raw('sum(transactions.qty) as quantity'), 'items.price', 'payment_methods.id', 'payment_methods.method_name')
                ->get();

        $credits = DB::table('transactions')
                ->join('items', 'items.id', '=', 'transactions.item_id')
                ->join('payment_types', 'payment_types.id', '=', 'transactions.payment_type_id')
                ->join('payment_methods', 'payment_methods.id', '=', 'transactions.payment_method_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->where(['transactions.dept' => 'utama', 'transactions.user_id' => auth()->user()->id, 'payment_methods.method_name' => 'kartu kredit'])
                ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))
                ->groupBy('payment_methods.id', 'items.id')
                ->select(DB::raw('sum(transactions.qty) as quantity'), 'items.price', 'payment_methods.id', 'payment_methods.method_name')
                ->get();

        $prices = DB::table('transactions')
                ->join('items', 'items.id', '=', 'transactions.item_id')
                ->join('users', 'users.id', '=', 'transactions.user_id')
                ->where(['transactions.dept' => 'utama', 'transactions.user_id' => auth()->user()->id])
                ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))
                ->groupBy('items.id')
                ->select(DB::raw('sum(transactions.qty) as quantity'), 'items.price', 'items.id')
                ->get();

        foreach($items as $item) {
            $quantity += $item->quantity;
            $trx = Transaction::where('transaction_number', $item->transaction_number)->groupBy('transaction_number')->first();
            $discount += (float) $trx->discount;
            $tax += (float) $trx->tax;
            $fee += (float) $trx->additional_fee;
        }

        foreach($prices as $price) {
            $total += (float)($price->price * $price->quantity);
        }

        foreach ($cashes as $c) {
            $cash += (float)($c->price * $c->quantity);
        }

        foreach ($ewallets as $e) {
            $ewallet += (float)($e->price * $e->quantity);
        }

        foreach ($debits as $d) {
            $debit += (float)($d->price * $d->quantity);
        }

        foreach ($transfers as $t) {
            $transfer += (float)($t->price * $t->quantity);
        }

        foreach ($credits as $c) {
            $credit += (float)($c->price * $c->quantity);
        }

        return [$quantity, $discount, $tax, $fee, $total, $cash, $ewallet, $debit, $transfer, $credit];
    }
}
