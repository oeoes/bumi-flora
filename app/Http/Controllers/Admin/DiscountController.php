<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Activity\Discount;
use App\Model\Relation\StakeHolder;
use Illuminate\Support\Facades\DB;

class DiscountController extends Controller
{
    public function discount_customer() {
        $customers = StakeHolder::where('type', 'customer')->get();
        $discounts = DB::table('discounts')
                    ->join('stake_holders', 'stake_holders.id', '=', 'discounts.stake_holder_id')
                    ->select('discounts.id as discount_customer_id', 'discounts.promo_name', 'discounts.status', 'discounts.value', 'stake_holders.name', 'stake_holders.id')->get();

        return view('pages.admin.discount-customer')->with(['customers' => $customers, 'discounts' => $discounts]);
    }

    public function store_discount_customer (Request $request) {
        Discount::create([
            'promo_name' => $request->promo_name,
            'promo_target' => 'customer',
            'stake_holder_id' => $request->customer_id,
            'value' => $request->value,
        ]);

        return response()->json(['status' => true, 'message' => 'Discount customer created']);
    }

    public function update_discount_customer (Request $request, $discount_customer_id) {
        $discount = Discount::find($discount_customer_id);
        $discount->update([
            'promo_name' => $request->promo_name,
            'stake_holder_id' => $request->stake_holder_id,
            'value' => $request->value,
            'status' => $request->status,
        ]);
        return back();
    }

    public function update_discount_item (Request $request, $discount_item_id) {
        $discount = Discount::find($discount_item_id);
        if ($discount->promo_item_type == 'item') {
            $discount->update([
                'promo_name' => $request->promo_name,
                'item_id' => $request->item_id,
                'value' => $request->value,
                'status' => $request->status,
            ]);
        } else {
            $discount->update([
                'promo_name' => $request->promo_name,
                'category_id' => $request->category_id,
                'value' => $request->value,
                'status' => $request->status,
            ]);
        }
        
        return back();
    }

    public function delete_discount_customer ($discount_customer_id) {
        $discount = Discount::find($discount_customer_id);
        $discount->delete();
        
        return back();
    }

    public function delete_discount_item ($discount_item_id) {
        $discount = Discount::find($discount_item_id);
        $discount->delete();
        
        return back();
    }

    public function discount_item() {
        $categories = DB::table('categories')->get();
        $items = DB::table('items')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->join('brands', 'brands.id', '=', 'items.brand_id')
                ->join('balances', 'balances.item_id', '=', 'items.id')
                ->where('balances.dept', 'utama')
                ->select('items.name', 'items.id', 'items.price', 'items.main_cost', 'units.unit')->get();

        $discounts = DB::table('discounts')
                    ->leftJoin('categories', 'categories.id', '=', 'discounts.category_id')
                    ->leftJoin('items', 'items.id', '=', 'discounts.item_id')
                    ->select('discounts.id as discount_item_id', 'discounts.promo_name', 'discounts.promo_item_type', 'discounts.promo_name', 'discounts.status', 'discounts.value', 'categories.category', 'categories.id as category_id', 'items.name', 'items.id as item_id')->get();

        return view('pages.admin.discount-item')->with(['items' => $items, 'categories' => $categories, 'discounts' => $discounts]);
    }

    public function store_discount_item (Request $request) {
        if ($request->promo_item_type == 'item') {
            Discount::create([
                'promo_name' => $request->promo_name,
                'promo_item_type' => $request->promo_item_type,
                'promo_target' => 'item',
                'item_id' => $request->item_id,
                'value' => $request->value,
            ]);
        }else {
            Discount::create([
                'promo_name' => $request->promo_name,
                'promo_item_type' => $request->promo_item_type,
                'promo_target' => 'item',
                'category_id' => $request->category_id,
                'value' => $request->value,
            ]);
        }

        return response()->json(['status' => true, 'message' => 'Discount customer created']);
    }

    public function get_customer_discount ($stake_holder_id) {
        $discounts = DB::table('discounts')
                    ->join('stake_holders', 'stake_holders.id', '=', 'discounts.stake_holder_id')
                    ->select('discounts.promo_name', 'discounts.status', 'discounts.value', 'stake_holders.name', 'stake_holders.id')
                    ->where(['stake_holders.id' => $stake_holder_id, 'discounts.status' => 1])->get();

        if (count($discounts)) return response()->json(['status' => true, 'message' => 'customer discount', 'data' => $discounts]);

        return response()->json(['status' => false, 'message' => 'tidak ada discount']);
    }
}
