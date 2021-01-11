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
use App\Http\Controllers\Activity\PrintDailyReportReceiptController;

class CashierController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:super_admin|root|cashier|online_storage']);
    }

    public function index()
    {
        $items = DB::table('items')
            ->join('stocks', 'items.id', '=', 'stocks.item_id')
            ->join('units', 'units.id', '=', 'items.unit_id')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->leftJoin('grosir_items', 'items.id', '=', 'grosir_items.item_id')
            ->leftJoin('discounts as discount_categories', 'categories.id', '=', 'discount_categories.category_id')
            ->leftJoin('discounts as discount_items', 'items.id', '=', 'discount_items.item_id')
            ->leftJoin('discount_periodes as discount_periode_item', 'discount_items.id', '=', 'discount_periode_item.discount_id')
            ->leftJoin('discount_periodes as discount_periode_category', 'discount_categories.id', '=', 'discount_periode_category.discount_id')
            ->where(['stocks.dept' => 'utama', 'items.published' => 1, 'items.deleted_at' => NULL])
            ->select('items.id', 'stocks.amount as stock', 'items.name', 'items.barcode', 'items.item_code', 'units.unit', 'categories.category', 'items.price as original_price', DB::raw('IFNULL(discount_items.value * CAST(discount_items.status as UNSIGNED), 0) as discount_item'), DB::raw('IFNULL(discount_categories.value * CAST(discount_categories.status as UNSIGNED), 0) as discount_category'), DB::raw('items.price - (IFNULL((items.price * discount_categories.value / 100) * CAST(discount_categories.status as UNSIGNED), 0)) as price_category'), DB::raw('items.price - (IFNULL((items.price * discount_items.value / 100) * CAST(discount_items.status as UNSIGNED), 0)) as price_item'), 'discount_periode_category.occurences as category_occurences', 'discount_periode_item.occurences as item_occurences', DB::raw('IFNULL(grosir_items.minimum_item, 0) as minimum_item'), 'grosir_items.price as grosir_price')->get();
        $customer = StakeHolder::where('type', 'customer')->distinct()->get();
        $payment_method = DB::table('payment_methods')->select('id', 'method_name')->get();

        return view('pages.activity.cashier')->with(['items' => $items, 'payment_method' => $payment_method, 'customers' => $customer]);
    }

    public function cashier_ecommerce()
    {
        $items = DB::table('items')
            ->join('stocks', 'items.id', '=', 'stocks.item_id')
            ->join('units', 'units.id', '=', 'items.unit_id')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->where(['stocks.dept' => 'ecommerce', 'items.deleted_at' => NULL])
            ->select('items.id', 'stocks.amount as stock', 'items.name', 'items.barcode', 'units.unit', 'items.price as price')->get();
        $customer = StakeHolder::where('type', 'customer')->distinct()->get();
        $payment_method = DB::table('payment_methods')->select('id', 'method_name')->get();

        return view('pages.activity.cashier-ecommerce')->with(['items' => $items, 'payment_method' => $payment_method, 'customers' => $customer]);
    }

    public function check_item(Request $request)
    {
        $item = DB::table('items')
            ->join('stocks', 'items.id', '=', 'stocks.item_id')
            ->join('units', 'units.id', '=', 'items.unit_id')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->leftJoin('grosir_items', 'items.id', '=', 'grosir_items.item_id')
            ->leftJoin('discounts as discount_categories', 'categories.id', '=', 'discount_categories.category_id')
            ->leftJoin('discounts as discount_items', 'items.id', '=', 'discount_items.item_id')
            ->leftJoin('discount_periodes as discount_periode_item', 'discount_items.id', '=', 'discount_periode_item.discount_id')
            ->leftJoin('discount_periodes as discount_periode_category', 'discount_categories.id', '=', 'discount_periode_category.discount_id')
            ->select('items.id', 'stocks.amount as stock', 'items.name', 'items.barcode', 'units.unit', 'items.price as original_price', DB::raw('IFNULL(discount_items.value * CAST(discount_items.status as UNSIGNED), 0) as discount_item'), DB::raw('IFNULL(discount_categories.value * CAST(discount_categories.status as UNSIGNED), 0) as discount_category'), DB::raw('items.price - (IFNULL((items.price * discount_categories.value / 100) * CAST(discount_categories.status as UNSIGNED), 0)) as price_category'), DB::raw('items.price - (IFNULL((items.price * discount_items.value / 100) * CAST(discount_items.status as UNSIGNED), 0)) as price_item'), 'discount_periode_category.occurences as category_occurences', 'discount_periode_item.occurences as item_occurences', DB::raw('IFNULL(grosir_items.minimum_item, 0) as minimum_item'), 'grosir_items.price as grosir_price')
            ->where(['stocks.dept' => $request->dept, 'items.published' => 1, 'items.barcode' => $request->code])->orWhere(['items.item_code' => $request->code])
            ->where('items.deleted_at', NULL)
            ->limit(1)
            ->get();


        if (count($item) < 1)
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan.']);

        $data = [
            'id' => $item[0]->id,
            'name' => $item[0]->name,
            'barcode' => $item[0]->barcode,
            'unit' => $item[0]->unit,
            'price' => '',
            'original_price' => $item[0]->original_price,
            'discount' => '',
            'stock' => $item[0]->stock,
            'minimum_item' => '',
            'grosir_price' => '',
        ];


        if ($item[0]->discount_item > 0) {
            if ($item[0]->minimum_item > 0) {
                $data['price'] = $item[0]->price_item;
                $data['discount'] = $item[0]->discount_item > 0 && in_array(strtolower(Carbon::now()->format('l')), unserialize($item[0]->item_occurences) ? unserialize($item[0]->item_occurences) : []) ? $item[0]->discount_item : 0;
                $data['minimum_item'] = $item[0]->minimum_item;
                $data['grosir_price'] = $item[0]->grosir_price;
            } else {
                $data['price'] = $item[0]->price_item;
                $data['discount'] = $item[0]->discount_item > 0 && in_array(strtolower(Carbon::now()->format('l')), unserialize($item[0]->item_occurences) ? unserialize($item[0]->item_occurences) : []) ? $item[0]->discount_item : 0;
                $data['minimum_item'] = 0;
                $data['grosir_price'] = 0;
            }
        } else if ($item[0]->discount_category > 0) {
            if ($item[0]->minimum_item > 0) {
                $data['price'] = $item[0]->price_category;
                $data['discount'] = $item[0]->discount_category > 0 && in_array(strtolower(Carbon::now()->format('l')), unserialize($item[0]->category_occurences) ? unserialize($item[0]->category_occurences) : []) ? $item[0]->discount_category : 0;
                $data['minimum_item'] = $item[0]->minimum_item;
                $data['grosir_price'] = $item[0]->grosir_price;
            } else {
                $data['price'] = $item[0]->price_item;
                $data['discount'] = $item[0]->discount_category > 0 && in_array(strtolower(Carbon::now()->format('l')), unserialize($item[0]->category_occurences) ? unserialize($item[0]->category_occurences) : []) ? $item[0]->discount_category : 0;
                $data['minimum_item'] = 0;
                $data['grosir_price'] = 0;
            }
        } else {
            if ($item[0]->minimum_item > 0) {
                $data['price'] = $item[0]->original_price;
                $data['discount'] = 0;
                $data['minimum_item'] = $item[0]->minimum_item;
                $data['grosir_price'] = $item[0]->grosir_price;
            } else {
                $data['price'] = $item[0]->price_item;
                $data['discount'] = 0;
                $data['minimum_item'] = 0;
                $data['grosir_price'] = 0;
            }
        }
        return response()->json(['status' => true, 'message' => 'Item', 'data' => $data]);
    }

    public function store_transaction(Request $request)
    {
        // kalau key transaction_id ada valuenya brrt ini request dari update transaksi
        $data_update = $request->transaction_id == '' ? false : true;

        $time = Carbon::now()->format('H:i:s');
        $payment_type = PaymentType::find($request->payment_type);
        $no_urut = Transaction::whereDate('created_at', Carbon::now()->format('Y-m-d'))->groupBy('transaction_time')->withTrashed()->get();
        $trx_number = (count($no_urut) + 1) . '/KSR/' . strtoupper($request->dept);

        // load data to transmit to printer
        $print_items = [];
        $total_price = 0;

        foreach ($request->items as $item) {
            $master_item = Item::find($item[0]);
            $stock = Stock::where(['item_id' => $item[0], 'dept' => $request->dept])->first();
            // count price of all items
            $total_price += ($item[3] * $item[1]);

            // update or store
            if ($data_update) {
                $parent_transaction = Transaction::find($request->transaction_id);
                $cur_transaction = Transaction::where(['transaction_number' => $parent_transaction->transaction_number, 'item_id' => $master_item->id])->first();

                // check if item exist in current transaction otherwise create one
                if($cur_transaction) {
                    self::update_transaction_history($item, $cur_transaction->id, $request, $payment_type);
                } else {
                    $discount_item = ($master_item->price * $item[1]) - ($item[3] * $item[1]); // $item[3] adalah price item klo ada discount
                    Transaction::create([
                        'user_id' => $parent_transaction->user_id,
                        'item_id' => $item[0],
                        'stake_holder_id' => $parent_transaction->stake_holder_id,
                        'transaction_number' => $parent_transaction->transaction_number,
                        'qty' => $item[1],
                        'dept' => $parent_transaction->dept,
                        'payment_method_id' => $payment_type ? $payment_type->payment_method_id : NULL,
                        'payment_type_id' => $payment_type ? $payment_type->id : NULL,
                        'discount' => ($request->discount / count($request->items)), // discount keseluruhan dibagi banyaknya item yg dibeli
                        'discount_item' => $discount_item, // discount item
                        'discount_customer' => ($request->customer_discount / count($request->items)), // discount customer
                        'additional_fee' => $request->additional_fee,
                        'tax' => $request->tax,
                        'transaction_time' => $parent_transaction->transaction_time
                    ]);

                    $stock->update([
                        'amount' => $stock->amount - $item[1]
                    ]);
                }
            } else {
                self::store_real_transaction($item, $request, $no_urut, $trx_number, $payment_type, $time);

                $stock->update([
                    'amount' => $stock->amount - $item[1]
                ]);
            }

            // array data receipt
            $data_item = [
                "name" => $item[2],
                "satuan" => $item[4],
                "price" => $item[6] > 0 && $item[1] >= $item[6] ? number_format($item[3]) : number_format(($item[3] * 100) / (100 - $item[5])),
                "qty" => $item[1],
                "total" => number_format($item[3] * $item[1]),
                "discount" => $item[5],
            ];
            // push ke array load data receipt
            array_push($print_items, $data_item);

            // warning if stock less than minimum stock or even zero left
            if ($stock->amount < $master_item->min_stock && $stock->amount > 0) {
                $title = $master_item->name . ' kurang dari stock minimum. [UTAMA]';
                $link = route('items.show', ['item' => $master_item->id]);
                $body = 'Jumlah item untuk ' . $master_item->name . ' kurang dari stock minimum. Segera lakukan restock sebelum kehabisan, untuk lebih detail klik tautan ini <a href="' . $link . '">detail.</a>';
                StockWarnNotification::create([
                    'title' => $title,
                    'body' => $body,
                    'urgency' => 1,
                ]);
            } else if ($stock->amount == 0) {
                $title = $master_item->name . ' stock habis. [UTAMA]';
                $link = route('items.show', ['item' => $master_item->id]);
                $body = 'Jumlah item untuk ' . $master_item->name . ' tersisa 0. Segera lakukan restock, klik tautan ini untuk lebih detail <a href="' . $link . '">detail.</a>';
                StockWarnNotification::create([
                    'title' => $title,
                    'body' => $body,
                    'urgency' => 2,
                ]);
            }
        }

        try {
            if ($request->dept !== 'ecommerce') { // kalau bukan ecomerce baru cetak receipt
                $bill = $total_price + $request->tax + $request->additional_fee - ($request->discount + $request->customer_discount);
                $cust = StakeHolder::find($request->customer);
                $calc = [
                    "total_price" => number_format($total_price),
                    "customer" => $cust ? $cust->name : 'Umum',
                    "discount" => number_format($request->discount + $request->customer_discount),
                    "fee" => number_format($request->additional_fee),
                    "tax" => number_format($request->tax),
                    "bill" => number_format($bill),
                    "cash" => $request->payment_type != 1 ? $payment_type->type_name : number_format($request->nominal),
                    "cashback" => $request->payment_type != 1 ? '-' : number_format($request->nominal - $bill),
                    "transaction_number" => $trx_number . '/' . Carbon::now()->format('Y-m-d')
                ];
                // print receipt
                PrintReceiptController::print_receipt($print_items, $calc);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 400);
        }

        return response()->json(['status' => true, 'message' => 'Transaction recorded', 'is_update' => $data_update ? true : false]);
    }

    public static function update_transaction_history($item, $trans, $request, $payment_type)
    {
        $transaction = Transaction::find($trans);
        $previous_qty = Transaction::where(['transaction_number' => $transaction->transaction_number, 'item_id' => $item[0]])->first();
        $stock = Stock::where(['item_id' => $item[0], 'dept' => $request->dept])->first();

        // ngitung discount item
        $default_item = Item::find($item[0]);
        $discount_item = ($default_item->price * $item[1]) - ($item[3] * $item[1]);

        $transaction->update([
            'stake_holder_id' => $request->customer,
            'qty' => $item[1],
            'payment_method_id' => $payment_type->payment_method_id,
            'payment_type_id' => $payment_type->id,
            'discount' => ($request->discount / count($request->items)), // discount keseluruhan dibagi banyaknya item yg dibeli
            'discount_item' => $discount_item, // discount item
            'discount_customer' => ($request->customer_discount / count($request->items)), // discount customer
            'additional_fee' => $request->additional_fee,
            'tax' => $request->tax,
        ]);

        // lakukan penambahan atau pengurangan stock bila qty terbaru berubah        
        if ($item[1] > $previous_qty->qty) {
            $stock->update([
                'amount' => $stock->amount - ((int)$item[1] - (int)$previous_qty->qty)
            ]);
        } else if ($item[1] < $previous_qty->qty) {
            $stock->update([
                'amount' => $stock->amount + ((int)$previous_qty->qty - (int)$item[1])
            ]);
        }
    }

    public static function store_real_transaction($item, $request, $no_urut, $trx_number, $payment_type, $time)
    {
        $default_item = Item::find($item[0]);
        $discount_item = ($default_item->price * $item[1]) - ($item[3] * $item[1]); // $item[3] adalah price item klo ada discount
        Transaction::create([
            'user_id' => auth()->user()->id,
            'item_id' => $item[0],
            'stake_holder_id' => $request->customer,
            'transaction_number' => $trx_number . '/' . Carbon::now()->format('Y-m-d') . '/' . auth()->user()->id,
            'qty' => $item[1],
            'dept' => $request->dept,
            'payment_method_id' => $payment_type ? $payment_type->payment_method_id : NULL,
            'payment_type_id' => $payment_type ? $payment_type->id : NULL,
            'discount' => ($request->discount / count($request->items)), // discount keseluruhan dibagi banyaknya item yg dibeli
            'discount_item' => $discount_item, // discount item
            'discount_customer' => ($request->customer_discount / count($request->items)), // discount customer
            'additional_fee' => $request->additional_fee,
            'tax' => $request->tax,
            'transaction_time' => $time
        ]);

        // record item keluar
        StorageRecord::create([
            'item_id' => $item[0],
            'dept' => $request->dept,
            'transaction_no' => (count($no_urut) + 1) . '/KSR/' . strtoupper($request->dept) . '/' . Carbon::now()->format('Y-m-d') . '/' . auth()->user()->id,
            'amount_out' => $item[1],
            'description' => $request->dept == 'utama' ? 'Penjualan offline' : 'Penjualan Online',
        ]);
    }

    public function cashier_history()
    {
        $items = DB::table('transactions')
            ->join('items', 'items.id', '=', 'transactions.item_id')
            ->join('units', 'units.id', '=', 'items.unit_id')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->join('brands', 'brands.id', '=', 'items.brand_id')
            ->join('balances', 'balances.item_id', '=', 'items.id')
            ->join('payment_types', 'payment_types.id', '=', 'transactions.payment_type_id')
            ->join('payment_methods', 'payment_methods.id', '=', 'transactions.payment_method_id')
            ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))
            ->where(['balances.dept' => 'utama', 'transactions.user_id' => auth()->user()->id, 'transactions.daily_complete' => 0, 'items.deleted_at' => NULL, 'transactions.deleted_at' => NULL])
            ->select('items.*', 'transactions.transaction_number', 'transactions.qty', 'payment_methods.method_name', 'payment_types.type_name', 'transactions.discount', 'transactions.transaction_time', 'transactions.created_at', 'units.unit', 'categories.category', 'brands.brand')->get();
// return self::daily_report_data();
        return view('pages.activity.cashier-history')->with(['items' => $items, 'data' => self::daily_report_data()]);
    }

    public function print_cashier_history()
    {
        try {
            PrintDailyReportReceiptController::print_daily_report_receipt(self::daily_report_data());

            DB::table('transactions')->where('user_id', auth()->user()->id)->update(['daily_complete' => 1]);

            return back();
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
    }

    public static function daily_report_data()
    {
        $discount = 0;
        $tax = 0;
        $fee = 0;

        $cash = 0;
        $cash_tax = 0;
        $cash_fee = 0;

        $ewallet = 0;
        $ewallet_tax = 0;
        $ewallet_fee = 0;

        $debit = 0;
        $debit_tax = 0;
        $debit_fee = 0;

        $transfer = 0;
        $transfer_tax = 0;
        $transfer_fee = 0;

        $credit = 0;
        $credit_tax = 0;
        $credit_fee = 0;

        $items = DB::table('transactions')
            ->join('items', 'items.id', '=', 'transactions.item_id')
            ->where(['transactions.dept' => 'utama', 'transactions.user_id' => auth()->user()->id,'items.deleted_at' => NULL, 'transactions.deleted_at' => NULL])
            ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))
            ->groupBy('transactions.transaction_number')
            ->select(DB::raw('(sum(transactions.discount) + sum(transactions.discount_item) + sum(transactions.discount_customer)) as discount'), 'transactions.tax', 'transactions.additional_fee')
            ->get();

        $cashes = DB::table('transactions')
            ->join('items', 'items.id', '=', 'transactions.item_id')
            ->join('payment_types', 'payment_types.id', '=', 'transactions.payment_type_id')
            ->join('payment_methods', 'payment_methods.id', '=', 'transactions.payment_method_id')
            ->join('users', 'users.id', '=', 'transactions.user_id')
            ->where(['transactions.dept' => 'utama', 'items.deleted_at' => NULL, 'transactions.deleted_at' => NULL, 'transactions.user_id' => auth()->user()->id, 'payment_methods.method_name' => 'cash'])
            ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))
            ->groupBy('transactions.transaction_number', 'transactions.item_id')
            ->select(DB::raw('sum(transactions.qty * items.price) as price'), DB::raw('sum(transactions.discount + transactions.discount_item + transactions.discount_customer) as discount'), 'transactions.transaction_number', 'transactions.additional_fee', 'transactions.tax')
            ->get();

        $ewallets = DB::table('transactions')
            ->join('items', 'items.id', '=', 'transactions.item_id')
            ->join('payment_types', 'payment_types.id', '=', 'transactions.payment_type_id')
            ->join('payment_methods', 'payment_methods.id', '=', 'transactions.payment_method_id')
            ->join('users', 'users.id', '=', 'transactions.user_id')
            ->where(['transactions.dept' => 'utama', 'items.deleted_at' => NULL, 'transactions.deleted_at' => NULL, 'transactions.user_id' => auth()->user()->id, 'payment_methods.method_name' => 'e-wallet'])
            ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))
            ->groupBy('transactions.transaction_number', 'transactions.item_id')
            ->select(DB::raw('sum(transactions.qty * items.price) as price'), DB::raw('sum(transactions.discount + transactions.discount_item + transactions.discount_customer) as discount'), 'transactions.transaction_number', 'transactions.additional_fee', 'transactions.tax')
            ->get();

        $debits = DB::table('transactions')
            ->join('items', 'items.id', '=', 'transactions.item_id')
            ->join('payment_types', 'payment_types.id', '=', 'transactions.payment_type_id')
            ->join('payment_methods', 'payment_methods.id', '=', 'transactions.payment_method_id')
            ->join('users', 'users.id', '=', 'transactions.user_id')
            ->where(['transactions.dept' => 'utama', 'items.deleted_at' => NULL, 'transactions.deleted_at' => NULL, 'transactions.user_id' => auth()->user()->id, 'payment_methods.method_name' => 'debit'])
            ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))
            ->groupBy('transactions.transaction_number', 'transactions.item_id')
            ->select(DB::raw('sum(transactions.qty * items.price) as price'), DB::raw('sum(transactions.discount + transactions.discount_item + transactions.discount_customer) as discount'), 'transactions.transaction_number', 'transactions.additional_fee', 'transactions.tax')
            ->get();

        $transfers = DB::table('transactions')
            ->join('items', 'items.id', '=', 'transactions.item_id')
            ->join('payment_types', 'payment_types.id', '=', 'transactions.payment_type_id')
            ->join('payment_methods', 'payment_methods.id', '=', 'transactions.payment_method_id')
            ->join('users', 'users.id', '=', 'transactions.user_id')
            ->where(['transactions.dept' => 'utama', 'items.deleted_at' => NULL, 'transactions.deleted_at' => NULL, 'transactions.user_id' => auth()->user()->id, 'payment_methods.method_name' => 'transfer'])
            ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))
            ->groupBy('transactions.transaction_number', 'transactions.item_id')
            ->select(DB::raw('sum(transactions.qty * items.price) as price'), DB::raw('sum(transactions.discount + transactions.discount_item + transactions.discount_customer) as discount'), 'transactions.transaction_number', 'transactions.additional_fee', 'transactions.tax')
            ->get();

        $credits = DB::table('transactions')
            ->join('items', 'items.id', '=', 'transactions.item_id')
            ->join('payment_types', 'payment_types.id', '=', 'transactions.payment_type_id')
            ->join('payment_methods', 'payment_methods.id', '=', 'transactions.payment_method_id')
            ->join('users', 'users.id', '=', 'transactions.user_id')
            ->where(['transactions.dept' => 'utama', 'items.deleted_at' => NULL, 'transactions.deleted_at' => NULL, 'transactions.user_id' => auth()->user()->id, 'payment_methods.method_name' => 'kartu kredit'])
            ->whereDate('transactions.created_at', Carbon::now()->format('Y-m-d'))
            ->groupBy('transactions.transaction_number', 'transactions.item_id')
            ->select(DB::raw('sum(transactions.qty * items.price) as price'), DB::raw('sum(transactions.discount + transactions.discount_item + transactions.discount_customer) as discount'), 'transactions.transaction_number', 'transactions.additional_fee', 'transactions.tax')
            ->get();

        foreach ($items as $item) {
            $discount += $item->discount;
            $tax += $item->tax;
            $fee += $item->additional_fee;
        }

        foreach ($cashes as $c) {
            $cash += (integer) $c->price - $c->discount;
            $cash_tax = (int) $c->tax;
            $cash_fee = (integer) $c->additional_fee;
        }

        foreach ($ewallets as $e) {
            $ewallet += (int) $e->price - $e->discount;
            $ewallet_tax = (int) $e->tax;
            $ewallet_fee = (int) $e->additional_fee;
        }

        foreach ($debits as $d) {
            $debit += (int) $d->price - $d->discount;
            $debit_tax = (int) $d->tax;
            $debit_fee = (int) $d->additional_fee;
        }

        foreach ($transfers as $t) {
            $transfer += (int) $t->price - $t->discount;
            $transfer_tax = (int) $t->tax;
            $transfer_fee = (int) $t->additional_fee;
        }

        foreach ($credits as $c) {
            $credit += (int) $c->price - $c->discount;
            $credit_tax = (int) $c->tax;
            $credit_fee = (int) $c->additional_fee;
        }

        return [
            'jumlah_transaksi' => count($items), 
            'total_discount' => $discount, 
            'total_pajak' => $tax, 
            'total_biaya' => $fee, 
            'total_tunai' => $cash + $cash_fee + $cash_tax,
            'total_debit' => $debit + $debit_fee + $debit_tax,
            'total_transfer' => $transfer + $transfer_fee + $transfer_tax,
            'total_ewallet' => $ewallet + $ewallet_fee + $ewallet_tax,
            'total_credit' => $credit + $credit_fee + $credit_tax,
        ];
    }
}
