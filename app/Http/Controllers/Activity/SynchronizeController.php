<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use App\Model\Activity\Discount;
use App\Model\Activity\DiscountPeriode;
use App\Model\Activity\GrosirItem;
use App\Model\Activity\Transaction;
use App\Model\MasterData\Brand;
use App\Model\MasterData\Category;
use App\Model\MasterData\Item;
use App\Model\MasterData\Unit;
use App\Model\Relation\StakeHolder;
use App\Model\Storage\Balance;
use App\Model\Storage\Stock;
use App\Model\Storage\StorageRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SynchronizeController extends Controller
{
    public function index()
    {
        return view('pages.activity.synchronize');
    }

    /**
     * Mengambil data terbaru dari live server ke local komputer.
     */
    public function local()
    {
        /**
         * Yg perlu di sinkronisasi
         * item, discount, discount customer, discount item, grosir, stock, balances, storage record (item keluar masuk)
         */
        $items = DB::connection('mysql2')->table('items')
            ->where(['deleted_at' => NULL])
            ->whereBetween('updated_at', [Carbon::now()->subDays(2), Carbon::now()->addDay()])->get();

        $customers = DB::connection('mysql2')->table('stake_holders')->where('type', 'customer')->get();

        $discounts = DB::connection('mysql2')->table('discounts')->get();

        $discount_periodes = DB::connection('mysql2')->table('discount_periodes')->get();

        $grosir = DB::connection('mysql2')->table('grosir_items')->get();


        try {
            // syncing items data
            foreach ($items as $item) {
                $local_item = Item::find($item->id);

                if ($local_item) {
                    $local_balance = Balance::where(['item_id' => $local_item->id, 'dept' => 'utama'])->first();
                    $live_balance = DB::connection('mysql2')->table('balances')->where(['item_id' => $item->id, 'dept' => 'utama'])->first();

                    $local_stock = Stock::where(['item_id' => $local_item->id, 'dept' => 'utama'])->first();
                    $live_stock = DB::connection('mysql2')->table('stocks')->where(['item_id' => $item->id, 'dept' => 'utama'])->first();

                    // update unit, brand, category, stakeholder/supplier, price, main_cost
                    $local_item->update([
                        'unit_id' => self::findOrCreate('unit', $item->unit_id),
                        'brand_id' => self::findOrCreate('brand', $item->brand_id),
                        'category_id' => self::findOrCreate('category', $item->category_id),
                        'stake_holder_id' => $item->stake_holder_id,
                        'main_cost' => $item->main_cost,
                        'price' => $item->price,
                    ]);

                    $local_balance->update([
                        'amount' => $live_balance->amount,
                    ]);
                    $local_stock->update([
                        'amount' => $live_stock->amount,
                    ]);
                } else {
                    DB::table('items')->insert([
                        'id' => $item->id,
                        'name' => $item->name,
                        'unit_id' => self::findOrCreate('unit', $item->unit_id),
                        'brand_id' => self::findOrCreate('brand', $item->brand_id),
                        'category_id' => self::findOrCreate('category', $item->category_id),
                        'stake_holder_id' => $item->stake_holder_id,
                        'base_unit' => $item->base_unit,
                        'base_unit_conversion' => $item->base_unit_conversion,
                        'cabinet' => $item->cabinet,
                        'description' => $item->description,
                        'main_cost' => $item->main_cost,
                        'barcode' => $item->barcode,
                        'price' => $item->price,
                        'min_stock' => $item->min_stock,
                        'published' => 1
                    ]);

                    $live_stock = DB::connection('mysql2')->table('stocks')->where(['item_id' => $item->id, 'dept' => 'utama'])->first();
                    $live_stock2 = DB::connection('mysql2')->table('stocks')->where(['item_id' => $item->id, 'dept' => 'gudang'])->first();

                    self::create_saldo_awal($item->id, 'utama', $live_stock->amount);
                    self::create_saldo_awal($item->id, 'gudang', $live_stock2->amount);
                }
            }

            // syncing customer
            foreach ($customers as $customer) {
                $local_cust = StakeHolder::where('email', $customer->email)->first();
                if (!$local_cust) {
                    StakeHolder::create([
                        'name' => $customer->name,
                        'address' => $customer->address,
                        'country' => $customer->country,
                        'province' => $customer->province,
                        'city' => $customer->city,
                        'postal_code' => $customer->postal_code,
                        'phone' => $customer->phone,
                        'email' => $customer->email,
                        'card_number' => $customer->card_number,
                        'owner' => $customer->owner,
                        'bank' => $customer->bank,
                        'type' => $customer->type,
                    ]);
                } else {
                    $local_cust->update([
                        'name' => $customer->name,
                        'address' => $customer->address,
                        'country' => $customer->country,
                        'province' => $customer->province,
                        'city' => $customer->city,
                        'postal_code' => $customer->postal_code,
                        'phone' => $customer->phone,
                        'email' => $customer->email,
                        'card_number' => $customer->card_number,
                        'owner' => $customer->owner,
                        'bank' => $customer->bank,
                        'type' => $customer->type,
                    ]);
                }
            }

            // syncing discount
            foreach ($discounts as $discount) {
                $disc = Discount::find($discount->id);
                if ($disc) {
                    $disc->update([
                        'promo_name' => $discount->promo_name,
                        'promo_target' => $discount->promo_target,
                        'stake_holder_id' => self::findOrCreate('customer', $discount->stake_holder_id),
                        'promo_item_type' => $discount->promo_item_type,
                        'item_id' => $discount->item_id,
                        'category_id' => $discount->category_id,
                        'value' => $discount->value,
                        'status' => $discount->status,
                    ]);
                } else {
                    DB::table('discounts')->insert([
                        'id' => $discount->id,
                        'promo_name' => $discount->promo_name,
                        'promo_target' => $discount->promo_target,
                        'stake_holder_id' => self::findOrCreate('customer', $discount->stake_holder_id),
                        'promo_item_type' => $discount->promo_item_type,
                        'item_id' => $discount->item_id,
                        'category_id' => $discount->category_id,
                        'value' => $discount->value,
                    ]);
                }
            }

            // syncing discount periode
            foreach ($discount_periodes as $dp) {
                $disc_per = DiscountPeriode::find($dp->id);
                if ($disc_per) {
                    $disc_per->update([
                        'discount_id' => $dp->discount_id,
                        'occurences' => $dp->occurences,
                    ]);
                } else {
                    DB::table('discount_periodes')->insert([
                        'id' => $dp->id,
                        'discount_id' => $dp->discount_id,
                        'occurences' => $dp->occurences,
                    ]);
                }
            }

            // syncing grosir
            foreach ($grosir as $gr) {
                $local_grosir = GrosirItem::find($gr->id);
                if ($local_grosir) {
                    $local_grosir->update([
                        'item_id' => $gr->item_id,
                        'minimum_item' => $gr->minimum_item,
                        'price' => $gr->price,
                    ]);
                } else {
                    DB::table('grosir_items')->insert([
                        'id' => $gr->id,
                        'item_id' => $gr->item_id,
                        'minimum_item' => $gr->minimum_item,
                        'price' => $gr->price,
                    ]);
                }
            }

            return response()->json(['status' => true, 'message' => 'Succesfully synced']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Error : ' . $th->getMessage()]);
        }
    }

    public static function create_saldo_awal($item_id, $dept, $amount)
    {
        $live_balance = DB::connection('mysql2')->table('balances')->where(['item_id' => $item_id, 'dept' => $dept])->first();
        DB::table('balances')->insert([
            'id' => $live_balance->id,
            'item_id' => $item_id,
            'amount' => $amount,
            'dept' => $dept,
        ]);

        $live_stock = DB::connection('mysql2')->table('stocks')->where(['item_id' => $item_id, 'dept' => $dept])->first();
        DB::table('stocks')->insert([
            'id' => $live_stock->id,
            'item_id' => $item_id,
            'amount' => $amount,
            'dept' => $dept,
        ]);

        $live_sr = DB::connection('mysql2')->table('storage_records')->where(['item_id' => $item_id, 'dept' => $dept])->first();
        DB::table('storage_records')->insert([
            'id' => $live_sr->id,
            'item_id' => $item_id,
            'dept' => $dept,
            'transaction_number' => 'init',
        ]);
    }

    public static function findOrCreate($entity, $key)
    {
        if ($entity === 'unit') {
            $unit = Unit::find($key);
            if ($unit) {
                return $unit->id;
            } else {
                $live_unit = DB::connection('mysql2')->table('units')->where('id', $key)->first();
                DB::table('units')->insert(['id' => $live_unit->id, 'unit' => $live_unit->unit, 'description' => $live_unit->description]);
                return $live_unit->id;
            }
        } else if ($entity === 'brand') {
            $brand = Brand::find($key);
            if ($brand) {
                return $brand->id;
            } else {
                $live_brand = DB::connection('mysql2')->table('brands')->where('id', $key)->first();
                DB::table('brands')->insert(['id' => $live_brand->id, 'brand' => $live_brand->brand, 'description' => $live_brand->description]);
                return $live_brand->id;
            }
        } else if ($entity === 'category') {
            $category = Category::find($key);
            if ($category) {
                return $category->id;
            } else {
                $live_category = DB::connection('mysql2')->table('categories')->where('id', $key)->first();
                DB::table('categories')->insert(['id' => $live_category->id, 'category' => $live_category->category, 'description' => $live_category->description]);
                return $live_category->id;
            }
        }
    }

    /**
     * Menyimpan data local ke server
     */
    public function live()
    {
        $transactions = Transaction::whereDate('created_at', Carbon::now()->format('Y-m-d'))
            ->where(['user_id' => auth()->user()->id, 'daily_complete' => 0])->get();
        try {
            foreach ($transactions as $transaction) {
                // update stock
                $live_stock = DB::connection('mysql2')->table('stocks')->where(['item_id' => $transaction->item_id, 'dept' => 'utama'])->first();
                DB::connection('mysql2')->table('stocks')->where(['item_id' => $transaction->item_id, 'dept' => 'utama'])->update([
                    'amount' => $live_stock->amount - $transaction->qty
                ]);

                // insert ke table transactions
                DB::connection('mysql2')->table('transactions')->insert([
                    'id' => $transaction->id,
                    'user_id' => $transaction->user_id,
                    'item_id' => $transaction->item_id,
                    'stake_holder_id' => $transaction->stake_holder_id,
                    'transaction_number' => $transaction->transaction_number,
                    'qty' => $transaction->qty,
                    'dept' => $transaction->dept,
                    'payment_method_id' => $transaction->payment_method_id,
                    'payment_type_id' => $transaction->payment_type_id,
                    'discount' => $transaction->discount,
                    'discount_item' => $transaction->discount_item, // discount item
                    'discount_customer' => $transaction->discount_customer,
                    'additional_fee' => $transaction->additional_fee,
                    'tax' => $transaction->tax,
                    'transaction_time' => $transaction->transaction_time,
                    'created_at' => $transaction->created_at,
                    'updated_at' => $transaction->updated_at,
                ]);

                // records storage
                $local_sr = StorageRecord::where(['item_id' => $transaction->item_id, 'transaction_no' => $transaction->transaction_number])->first();
                DB::connection('mysql2')->table('storage_records')->insert([
                    'id' => $local_sr->id,
                    'item_id' => $transaction->item_id,
                    'dept' => $transaction->dept,
                    'transaction_no' => $transaction->transaction_number,
                    'amount_out' => $transaction->amount_out,
                    'description' => $transaction->description,
                    'created_at' => $transaction->created_at,
                    'updated_at' => $transaction->updated_at,
                ]);
            }

            DB::table('transactions')->whereDate('created_at', Carbon::now()->format('Y-m-d'))
            ->where('user_id', auth()->user()->id)->update(['daily_complete' => 1]);

            DB::connection('mysql2')->table('transactions')->whereDate('created_at', Carbon::now()->format('Y-m-d'))
            ->where('user_id', auth()->user()->id)->update(['daily_complete' => 1]);

            return response()->json(['status' => true, 'message' => 'Sucessfully uploaded.']);
        } catch (\Throwable $th) {

            return response()->json(['status' => false, 'message' => 'Error uploading : ' . $th->getMessage()]);
        }
    }
}
