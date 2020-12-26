<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Model\Storage\StorageRecord;
use App\Model\Storage\Balance;
use App\Model\Storage\Stock;
use Carbon\Carbon;
use App\Model\Activity\Transaction;
use App\Model\Relation\StakeHolder;

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
            $no_urut = StorageRecord::where('amount_in', '!=', 'NULL')->get();
            StorageRecord::create([
                'item_id' => $request->item_id,
                'dept' => $request->dept,
                'transaction_no' => (count($no_urut)+1) . '/MASUK/' . strtoupper($request->dept) . '/' . Carbon::now()->format('Y-m-d'),
                'amount_in' => $request->amount,
                'description' => $request->description,
            ]);
            $stock->update([
                'amount' => $stock->amount + $request->amount
            ]);
        } else {
            $no_urut = StorageRecord::where('amount_out', '!=', 'NULL')->get();
            StorageRecord::create([
                'item_id' => $request->item_id,
                'dept' => $request->dept,
                'transaction_no' => (count($no_urut)+1) . '/MASUK/' . strtoupper($request->dept) . '/' . Carbon::now()->format('Y-m-d'),
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
    public function update(Request $request, Transaction $record)
    {
        $record->update([
            'qty' => $request->qty,
            'discount' => $request->discount,
            'additional_fee' => $request->additional_fee,
            'tax' => $request->tax,
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $record)
    {
        $record->delete();
        return back();
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

    public function offline_transaction_history () {
        $items = DB::table('transactions')
                ->join('items', 'items.id', '=', 'transactions.item_id')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->leftJoin('stake_holders', 'stake_holders.id', '=', 'transactions.stake_holder_id')
                ->join('brands', 'brands.id', '=', 'items.brand_id')
                ->join('payment_types', 'payment_types.id', '=', 'transactions.payment_type_id')
                ->join('payment_methods', 'payment_methods.id', '=', 'transactions.payment_method_id')
                ->where(['transactions.dept' => 'utama'])
                ->latest()
                ->groupBy('transactions.transaction_number', 'transactions.transaction_time')
                ->select(DB::raw('sum(transactions.qty) as quantity'), 'transactions.id', 'transactions.dept', 'stake_holders.name as customer', 'transactions.transaction_number', 'payment_methods.method_name', 'payment_types.type_name', 'transactions.transaction_time', 'transactions.created_at')
                ->get();

        return view('pages.persediaan.transaksi-offline-history')->with('items', $items);
    }

    public function online_transaction_history () {

        return view('pages.persediaan.transaksi-online-history');
    }

    public function get_transaction_data ($dept) {
        $items = DB::table('transactions')
                ->join('items', 'items.id', '=', 'transactions.item_id')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->leftJoin('stake_holders', 'stake_holders.id', '=', 'transactions.stake_holder_id')
                ->join('brands', 'brands.id', '=', 'items.brand_id')
                ->leftJoin('payment_types', 'payment_types.id', '=', 'transactions.payment_type_id')
                ->leftJoin('payment_methods', 'payment_methods.id', '=', 'transactions.payment_method_id')
                ->where(['transactions.dept' => $dept])
                ->latest()
                ->groupBy('transactions.transaction_number', 'transactions.transaction_time')
                ->select(DB::raw('sum(transactions.qty) as quantity'), 'transactions.id', 'transactions.dept', 'stake_holders.name as customer', 'transactions.transaction_number', 'payment_methods.method_name', 'payment_types.type_name', 'transactions.transaction_time', 'transactions.created_at')
                ->get();
        return response()->json(['status' => count($items) ? true : false, 'data' => $items]);
    }

    public function get_transaction_data_sorted ($dept, $from, $to) {
        $items = DB::table('transactions')
                ->join('items', 'items.id', '=', 'transactions.item_id')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->leftJoin('stake_holders', 'stake_holders.id', '=', 'transactions.stake_holder_id')
                ->join('brands', 'brands.id', '=', 'items.brand_id')
                ->leftJoin('payment_types', 'payment_types.id', '=', 'transactions.payment_type_id')
                ->leftJoin('payment_methods', 'payment_methods.id', '=', 'transactions.payment_method_id')
                ->where(['transactions.dept' => $dept])
                ->whereBetween(DB::raw('DATE(transactions.created_at)'), [$from, $to])
                ->latest()
                ->groupBy('transactions.transaction_number', 'transactions.transaction_time')
                ->select(DB::raw('sum(transactions.qty) as quantity'), 'transactions.id', 'transactions.dept', 'stake_holders.name as customer', 'transactions.transaction_number', 'payment_methods.method_name', 'payment_types.type_name', 'transactions.transaction_time', 'transactions.created_at')
                ->get();
        return response()->json(['status' => count($items) ? true : false, 'data' => $items]);
    }

    public function detail_transaction_history ($transaction_id, $dept) {
        $transaction = Transaction::find($transaction_id);
        $base_transaction = DB::table('transactions')
                        ->leftJoin('payment_types', 'payment_types.id', '=', 'transactions.payment_type_id')
                        ->leftJoin('payment_methods', 'payment_methods.id', '=', 'transactions.payment_method_id')
                        ->leftJoin('stake_holders', 'stake_holders.id', '=', 'transactions.stake_holder_id')
                        ->where(['transactions.id' => $transaction_id, 'transactions.dept' => $dept])
                        ->select('transactions.id', 'transactions.transaction_number', 'transactions.created_at', 'transactions.transaction_time', 'stake_holders.name as customer', 'payment_types.type_name', 'payment_methods.method_name')
                        ->first();

        $items = DB::table('transactions')
                ->join('items', 'items.id', '=', 'transactions.item_id')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->leftJoin('stake_holders', 'stake_holders.id', '=', 'transactions.stake_holder_id')
                ->join('brands', 'brands.id', '=', 'items.brand_id')
                ->join('balances', 'balances.item_id', '=', 'items.id')
                ->leftJoin('payment_types', 'payment_types.id', '=', 'transactions.payment_type_id')
                ->leftJoin('payment_methods', 'payment_methods.id', '=', 'transactions.payment_method_id')
                ->where(['balances.dept' => $dept, 'transactions.transaction_number' => $transaction->transaction_number])
                ->orderBy('transactions.created_at')
                ->select('items.id as item_id', 'items.name', 'items.main_cost', 'items.price', 'stake_holders.name as customer', 'transactions.id as transaction_id', 'transactions.transaction_number', 'transactions.qty', 'payment_methods.method_name', 'payment_types.type_name', 'transactions.discount', 'transactions.additional_fee', 'transactions.tax', 'transactions.transaction_time', 'transactions.created_at', 'units.unit', 'categories.category', 'brands.brand')
                ->get();

        return view('pages.persediaan.detail-transaksi-history')->with(['items' => $items, 'base' => $base_transaction]);
    }

    public function transfer_item (Request $request) {
        // perlakuan untuk penyimpanan ecommerce
        if ($request->to == 'ecommerce') {
            $stock_item = Stock::where(['item_id' => $request->item_id, 'dept' => $request->to])->first();
            if (!$stock_item) {
                Balance::create([
                    'item_id' => $request->item_id,
                    'amount' => 0,
                    'dept' => $request->to,
                ]);
                Stock::create([
                    'item_id' => $request->item_id,
                    'amount' => 0,
                    'dept' => $request->to,
                ]);
            }
        }


        $from = Stock::where(['item_id' => $request->item_id, 'dept' => $request->from])->first();
        $to = Stock::where(['item_id' => $request->item_id, 'dept' => $request->to])->first();

        // cek kalo saldo setelah dikurangi tidak kurang dari 0
        if ( $from->amount - $request->amount < 0) return back();


        $from->update(['amount' => $from->amount - $request->amount]);
        $to->update(['amount' => $to->amount + $request->amount]);

        $no_urut_in = StorageRecord::where('amount_in', '!=', 'NULL')->get();
        $no_urut_out = StorageRecord::where('amount_out', '!=', 'NULL')->get();

        // record item masuk
        StorageRecord::create([
            'item_id' => $request->item_id,
            'dept' => $request->to,
            'transaction_no' => (count($no_urut_in)+1) . '/MASUK/' . strtoupper($request->to) . '/' . Carbon::now()->format('Y-m-d'),
            'amount_in' => $request->amount,
            'description' => 'Transfer item dari ' . $request->from,
        ]);

        // record item keluar
        StorageRecord::create([
            'item_id' => $request->item_id,
            'dept' => $request->from,
            'transaction_no' => (count($no_urut_out)+1) . '/KELUAR/' . strtoupper($request->from) . '/' . Carbon::now()->format('Y-m-d'),
            'amount_out' => $request->amount,
            'description' => 'Transfer item ke penyimpanan ' . $request->to,
        ]);
        
        return back();
    }

    public function live_edit_transaction (Transaction $transaction) {
        $list_of_items = [];
        // data seluruh transaksi
        $transactions = DB::table('transactions')->join('items', 'items.id', '=', 'transactions.item_id')
                    ->where('transactions.transaction_number', $transaction->transaction_number)
                    ->select('items.barcode', 'transactions.dept', 'transactions.qty')
                    ->get();

        // item untuk di modal search item
        $items = DB::table('items')
            ->join('stocks', 'items.id', '=', 'stocks.item_id')
            ->join('units', 'units.id', '=', 'items.unit_id')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->leftJoin('grosir_items', 'items.id', '=', 'grosir_items.item_id')
            ->leftJoin('discounts as discount_categories', 'categories.id', '=', 'discount_categories.category_id')
            ->leftJoin('discounts as discount_items', 'items.id', '=', 'discount_items.item_id')
            ->leftJoin('discount_periodes as discount_periode_item', 'discount_items.id', '=', 'discount_periode_item.discount_id')
            ->leftJoin('discount_periodes as discount_periode_category', 'discount_categories.id', '=', 'discount_periode_category.discount_id')
            ->where('stocks.dept', $transaction->dept)
            ->select('items.id', 'stocks.amount as stock', 'items.name', 'items.barcode', 'units.unit', 'items.price as original_price', DB::raw('IFNULL(discount_items.value * CAST(discount_items.status as UNSIGNED), 0) as discount_item'), DB::raw('IFNULL(discount_categories.value * CAST(discount_categories.status as UNSIGNED), 0) as discount_category'), DB::raw('items.price - (IFNULL((items.price * discount_categories.value / 100) * CAST(discount_categories.status as UNSIGNED), 0)) as price_category'), DB::raw('items.price - (IFNULL((items.price * discount_items.value / 100) * CAST(discount_items.status as UNSIGNED), 0)) as price_item'), 'discount_periode_category.occurences as category_occurences', 'discount_periode_item.occurences as item_occurences', DB::raw('IFNULL(grosir_items.minimum_item, 0) as minimum_item'), 'grosir_items.price as grosir_price')->get();

        foreach ($transactions as $trans) {
            $item = self::transaction_toArray($trans->barcode, $trans->dept, $trans->qty);
            array_push($list_of_items, [$item['id'], $item['name'], $item['barcode'], $item['unit'], $item['qty'], $item['price'], $item['original_price'], $item['discount'], $item['stock'], $item['minimum_item'], $item['grosir_price'], $item['before_grosir_price']]);
        }

        $customer = StakeHolder::where('type', 'customer')->distinct()->get();
        $payment_method = DB::table('payment_methods')->select('id', 'method_name')->get();

        return view('pages.activity.edit-transaksi.cashier-edit')->with(['items' => $items, 'transaction_id' => $transaction->id, 'payment_method' => $payment_method, 'customers' => $customer, 'dept' => $transaction->dept, 'cashier' => [
            'tax' => $transaction->tax,
            'additional_fee' => $transaction->additional_fee,
            'payment_type' => $transaction->payment_type_id,
            'discount' => $transaction->discount,
            'cashier_items' => $list_of_items
        ]]);
    }

    /**
     * get data untuk diretrieve ke halaman edit kasir
     */
    public static function transaction_toArray ($barcode, $dept, $quantity) {
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
            ->where(['stocks.dept' => $dept, 'items.published' => 1, 'items.barcode' => $barcode])
            ->get();

        $data = [
            'id' => $item[0]->id,
            'name' => $item[0]->name,
            'barcode' => $item[0]->barcode,
            'unit' => $item[0]->unit,
            'qty' => $quantity,
            'price' => '',
            'original_price' => $item[0]->original_price,
            'discount' => '',
            'stock' => $item[0]->stock,
            'minimum_item' => '',
            'grosir_price' => '',
            'before_grosir_price' => $item[0]->original_price,
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

        return $data;
    }
}
