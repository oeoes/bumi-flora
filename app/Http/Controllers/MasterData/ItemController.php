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
use Illuminate\Support\Facades\Storage;
use App\Exports\MasterDataExport;
use DataTables;
use Excel;

class ItemController extends Controller
{
    public function index()
    {   
        return view('pages.data-item.items');
    }

    public function data_item_page ($published) {
        $data = self::items_query()->where('published', $published);
        return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('price', function($row) {
                    $content = 'Rp.'.number_format((float) $row->price);
                    return $content;
                })
                ->addColumn('main_cost', function($row) {
                    $content = 'Rp.'.number_format((float) $row->main_cost);
                    return $content;
                })
                ->addColumn('action', function($row) {
                    $btn = '<a href="'.route('items.show', ['item' => $row->id]).'" class="btn btn-sm btn-outline-info rounded-pill">View Detail</a>';
    
                    return $btn;
                })
                ->rawColumns(['action', 'main_cost', 'price'])
                ->make(true);
    }

    public static function items_query () {
        return DB::table('items')
                ->select('id', 'name', 'barcode', 'base_unit', 'base_unit_conversion', 'main_cost', 'price')
                ->where('deleted_at', NULL);
    }

    public static function items_show_query() {
        return DB::table('items')
                ->join('units', 'units.id', '=', 'items.unit_id')
                ->join('categories', 'categories.id', '=', 'items.category_id')
                ->join('brands', 'brands.id', '=', 'items.brand_id')
                ->join('stocks', 'stocks.item_id', '=', 'items.id')
                ->where('stocks.dept', 'utama')
                ->select('items.id', 'items.name', 'items.barcode', 'items.min_stock', 'items.description', 'items.cabinet', 'items.main_cost', 'items.price', 'items.base_unit', 'items.base_unit_conversion', 'units.unit', 'brands.brand', 'categories.category', 'stocks.dept', 'stocks.amount as stock');
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
        return view('pages.data-item.show-item')->with('item', self::items_show_query()->where('items.id', $item)->first());
    }

    public function destroy(Item $item) {
        $item->delete();
        return redirect()->route('items.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        $units = Unit::all();
        $categories = Category::all();
        $brands = Brand::all();

        return view('pages.data-item.edit-item')->with(['item' => $item, 'units' => $units, 'categories' => $categories, 'brands' => $brands]);
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

    public function update(Request $request, Item $item) {
        $item->update([
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

        session()->flash('message', 'OK! Data berhasil diperbarui.');
        return redirect()->route('items.index');
    }

    public function export_item () {
        return Excel::download(new MasterDataExport, 'master-data.xlsx');
    }

    public function import_item () {
        $file = request()->file('file');
        $extension = $file->getClientOriginalExtension();

        // save file
        request()->file('file')->storeAs('/product_files', 'import_item_data'.'.'.strtolower($extension));

        $csv_file = fopen(storage_path('app/product_files/import_item_data.csv'),"r");
        
        $flag = true;
        while (($line = fgetcsv($csv_file)) !== FALSE) {
            if($flag) {
                $flag = false;
                continue;
            } else {
                if(!Item::where('barcode', $line[0])->first()) { // prevent duplicate item
                    $item = Item::create([
                        'barcode' => $line[0],
                        'name' => $line[1],
                        'category_id' => self::findOrCreate('category', $line[2]),
                        'unit_id' => self::findOrCreate('unit', $line[3]),
                        'brand_id' => self::findOrCreate('brand', $line[4]),
                        'base_unit' => $line[5],
                        'base_unit_conversion' => $line[6],
                        'main_cost' => $line[7],
                        'price' => $line[8],
                        'min_stock' => $line[9],
                        'cabinet' => self::isNull($line[10]),
                        'stake_holder_id' => self::isNull($line[11]),
                        'description' => self::isNull($line[12]),
                    ]);

                    self::create_saldo_awal($item->id, 'utama');
                    self::create_saldo_awal($item->id, 'gudang');   
                self::create_saldo_awal($item->id, 'gudang');
                    self::create_saldo_awal($item->id, 'gudang');   
                self::create_saldo_awal($item->id, 'gudang');
                    self::create_saldo_awal($item->id, 'gudang');   
                }
            }
        }
        // delete right away
        Storage::delete('product_files/import_item_data.csv');
        
        return response()->json(['status' => true, 'message' => 'File uploaded successfully']);
    }

    /**
     * $entity = [unit, brand, category]
     */
    public static function findOrCreate ($entity, $key) {
        if ($entity == 'unit') {
            $unit = Unit::where('unit', strtolower($key))->first();
            if ($unit) {
                return $unit->id;
            } else {
                $new_unit = Unit::create(['unit' => $key, 'description' => $key]);
                return $new_unit->id;
            }
        }
        else if ($entity == 'brand') {
            $brand = Brand::where('brand', strtolower($key))->first();
            if($brand) {
                return $brand->id;
            } else {
                $new_brand = Brand::create(['brand' => $key, 'description' => $key]);
                return $new_brand->id;
            }
        }
        else {
            $category = Category::where('category', strtolower($key))->first();
            if($category) {
                return $category->id;
            } else {
                $new_category = Category::create(['category' => $key, 'description' => $key]);
                return $new_category->id;
            }
        }
    }

    public static function isNull($var) {
        if($var == '')
            return NULL;
        return $var;
    }
}
