<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\MasterData\Item;
use Illuminate\Support\Facades\DB;
use App\Model\MasterData\Brand;
use App\Model\MasterData\Unit;
use App\Model\MasterData\Category;
use App\Model\Storage\Balance;
use App\Model\Storage\Stock;

class ItemController extends Controller
{
    public function index()
    {   
        return view('pages.data-item.items')->with('items', self::items_query()->get());
    }

    public static function items_query () {
        return DB::table('items')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->join('brands', 'brands.id', '=', 'items.brand_id')
                ->join('balances', 'balances.item_id', '=', 'items.id')
                ->select('items.*', 'units.unit', 'categories.category', 'brands.brand');
    }

    public function create()
    {
        $brands = Brand::all();
        $units = Unit::all();
        $categories = Category::all();

        return view('pages.data-item.add-item', ['brands' => $brands, 'units' => $units, 'categories' => $categories]);
    }

    public function store(Request $request)
    {
        $item = Item::create([
            'name' => $request->name,
            'code' => $request->code,
            'image' => $request->image,
            'unit_id' => $request->unit,
            'brand_id' => $request->brand,
            'category_id' => $request->category,
            'stake_holder_id' => $request->stake_holder,
            'type' => $request->type,
            'cabinet' => $request->cabinet,
            'sale_status' => $request->sale_status,
            'description' => $request->description,
            'main_cost' => $request->main_cost,
            'barcode' => $request->barcode,
            'price' => $request->price,
            'min_stock' => $request->min_stock,
        ]);

        self::create_saldo_awal($item->id, 'utama');
        self::create_saldo_awal($item->id, 'gudang');

        return back();
    }

    public static function create_saldo_awal ($item_id, $dept) {
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($item)
    {
        return view('pages.data-item.show-item')->with('item', self::items_query()->where('items.id', $item)->first());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($item)
    {
        return view('pages.data-item.edit-item');
    }

    public function filter_item (Request $request) {
        $items = self::items_query();
        $query = self::add_query_on_filter($items, $request);

        return view('pages.data-item.items')->with('items', $query->get());
    }

    public static function add_query_on_filter ($query, $request) {
        if ($request->dept != 'all') {
            $query = $query->where('balances.dept', $request->dept);
        }

        return $query;
    }
}
