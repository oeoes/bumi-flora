<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use App\Model\MasterData\Brand;
use App\Model\MasterData\Category;
use App\Model\MasterData\Item;
use App\Model\MasterData\Unit;
use App\Model\Storage\Balance;
use App\Model\Storage\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StagingItemController extends Controller
{
    public function index () {
        $brands = Brand::all();
        $units = Unit::all();
        $categories = Category::all();

        $items = DB::table('items')
            ->join('units', 'units.id', '=', 'items.unit_id')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->join('brands', 'brands.id', '=', 'items.brand_id')
            ->join('stocks', 'stocks.item_id', '=', 'items.id')
            ->where(['stocks.dept' => 'utama', 'published' => 0,'items.deleted_at' => NULL])
            ->select('items.id', 'items.name', 'items.barcode', 'items.min_stock', 'items.description', 'items.cabinet', 'items.main_cost', 'items.price', 'items.base_unit', 'items.base_unit_conversion', 'units.unit', 'units.id as unit_id', 'brands.brand', 'brands.id as brand_id', 'categories.category', 'categories.id as category_id', 'stocks.dept', 'stocks.amount as stock')
            ->get();

        return view('pages.stages.index')->with(['items' => $items,'brands' => $brands, 'units' => $units, 'categories' => $categories]);
    }

    public function create () {
        $brands = Brand::all();
        $units = Unit::all();
        $categories = Category::all();

        return view('pages.stages.create-item', ['brands' => $brands, 'units' => $units, 'categories' => $categories]);
    }

    public function store(Request $request) {
        $item = Item::create([
            'name' => $request->name,
            'unit_id' => $request->unit,
            'brand_id' => $request->brand,
            'category_id' => $request->category,
            'stake_holder_id' => $request->stake_holder,
            'base_unit' => $request->base_unit,
            'base_unit_conversion' => $request->base_unit_conversion,
            'cabinet' => $request->cabinet,
            'description' => $request->description,
            'main_cost' => $request->main_cost,
            'barcode' => $request->barcode,
            'price' => $request->price,
            'min_stock' => $request->min_stock,
        ]);

        self::create_saldo_awal($item->id, 'utama');
        self::create_saldo_awal($item->id, 'gudang');

        session()->flash('message', 'Yeay! Item berhasil ditambahkan.');
        return back();
    }

    public function update(Request $request, Item $stage) {
        $stage->update([
            'name' => $request->name,
            'unit_id' => $request->unit,
            'brand_id' => $request->brand,
            'category_id' => $request->category,
            'stake_holder_id' => $request->stake_holder,
            'base_unit' => $request->base_unit,
            'base_unit_conversion' => $request->base_unit_conversion,
            'cabinet' => $request->cabinet,
            'description' => $request->description,
            'main_cost' => $request->main_cost,
            'barcode' => $request->barcode,
            'price' => $request->price,
            'min_stock' => $request->min_stock,
        ]);
        return back();
    }

    public function complete_item(Request $request, Item $stage) {
        $stage->update([
            'main_cost' => $request->main_cost,
            'price' => $request->price,
            'published' => 1
        ]);
        return back();
    }

    public static function create_saldo_awal($item_id, $dept)
    {
        Balance::create([
            'item_id' => $item_id,
            'amount' => 0,
            'dept' => $dept,
        ]);
        Stock::create([
            'item_id' => $item_id,
            'amount' => 0,
            'dept' => $dept,
        ]);
    }

    public function destroy (Item $stage) {
        $stage->delete();
        return back();
    }
}
