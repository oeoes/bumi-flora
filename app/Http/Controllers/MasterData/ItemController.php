<?php

namespace App\Http\Controllers\MasterData;

use App\Exports\DynamicDataExport;
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
use Carbon\Carbon;
use DataTables;
use Excel;
use PDF;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ItemController extends Controller
{
    public function index()
    {
        return view('pages.data-item.items')->with(['items' => self::filter_query(), 'categories' => Category::all()]);
    }

    public function data_item_page($published)
    {
        $data = self::items_query()->where('published', $published);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('price', function ($row) {
                $content = 'Rp.' . number_format((float) $row->price);
                return $content;
            })
            ->addColumn('main_cost', function ($row) {
                $content = 'Rp.' . number_format((float) $row->main_cost);
                return $content;
            })
            ->addColumn('action', function ($row) {
                $btn = '<a href="' . route('items.show', ['item' => $row->id]) . '" class="btn btn-sm btn-outline-info rounded-pill">View Detail</a>';

                return $btn;
            })
            ->rawColumns(['action', 'main_cost', 'price'])
            ->make(true);
    }

    public static function items_query()
    {
        return DB::table('items')
            ->select('id', 'name', 'barcode', 'base_unit', 'base_unit_conversion', 'main_cost', 'price')
            ->where('deleted_at', NULL);
    }

    public static function items_show_query()
    {
        return DB::table('items')
            ->join('units', 'units.id', '=', 'items.unit_id')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->join('brands', 'brands.id', '=', 'items.brand_id')
            ->join('stocks', 'stocks.item_id', '=', 'items.id')
            ->where(['stocks.dept' => 'utama', 'items.deleted_at' => NULL])
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
            'published' => 1
        ]);

        self::create_saldo_awal($item->id, 'utama', 0);
        self::create_saldo_awal($item->id, 'gudang', 0);

        session()->flash('message', 'Yeay! Item berhasil ditambahkan.');
        return back();
    }

    public static function create_saldo_awal($item_id, $dept, $amount)
    {
        Balance::create([
            'item_id' => $item_id,
            'amount' => $amount,
            'dept' => $dept,
        ]);
        Stock::create([
            'item_id' => $item_id,
            'amount' => $amount,
            'dept' => $dept,
        ]);
    }

    public static function update_saldo_awal($item_id, $dept, $amount = 0)
    {
        $balance = Balance::where(['item_id' => $item_id, 'dept' => $dept])->first();
        $stock = Stock::where(['item_id' => $item_id, 'dept' => $dept])->first();

        $balance->update(['amount' => $amount]);
        $stock->update(['amount' => $amount]);
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

    public function destroy(Item $item)
    {
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

    public function filter_item(Request $request)
    {
        $items = self::items_query();
        $query = self::add_query_on_filter($items, $request);

        return view('pages.data-item.items')->with('items', $query->get());
    }

    public static function add_query_on_filter($query, $request)
    {
        if ($request->dept != 'all') {
            $query = $query->where('balances.dept', $request->dept);
        }

        return $query;
    }

    public function update(Request $request, Item $item)
    {
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

    public function export_item($dept)
    {
        return Excel::download(new MasterDataExport($dept), 'master-data.xlsx');
    }

    public function export_data_item(Request $request)
    {
        if ($request->fileType === 'pdf') {
            $items = self::generate_query_pdf($request->reportType);
            $pdf = PDF::loadView('pdf-template.data-item-dynamic', ['items' => $items, 'reportType' => $request->reportType])->setPaper('a4', 'landscape');

            $content = $pdf->download()->getOriginalContent();
            Storage::disk('local')->put('master-data.pdf', $content);

            return response()->download(storage_path() . '/app/master-data.pdf', 'master-data.pdf', ['Content-Type' => ' application/pdf'])->deleteFileAfterSend();
        } else {
            Excel::store(new DynamicDataExport($request->reportType), 'master-data.xlsx');
            return response()->download(storage_path() . '/app/master-data.xlsx', 'master-data.xlsx', ['Content-Type' => ' application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])->deleteFileAfterSend();
        }
    }

    public static function generate_query_pdf($reportType)
    {
        switch ($reportType) {
            case 'main_cost':
                return DB::table('items')
                    ->join('units', 'units.id', '=', 'items.unit_id')
                    ->join('categories', 'categories.id', '=', 'items.category_id')
                    ->join('brands', 'brands.id', '=', 'items.brand_id')
                    ->join('stocks', 'stocks.item_id', '=', 'items.id')
                    ->leftJoin('stake_holders', 'stake_holders.id', '=', 'items.stake_holder_id')
                    ->where(['items.deleted_at' => NULL, 'items.published' => 1])
                    ->select('items.item_code', 'items.barcode', 'items.name as item', 'categories.category', 'units.unit', 'brands.brand', 'items.main_cost', 'items.base_unit', 'items.base_unit_conversion')->distinct()->get();
                break;

            case 'price':
                return DB::table('items')
                    ->join('units', 'units.id', '=', 'items.unit_id')
                    ->join('categories', 'categories.id', '=', 'items.category_id')
                    ->join('brands', 'brands.id', '=', 'items.brand_id')
                    ->join('stocks', 'stocks.item_id', '=', 'items.id')
                    ->leftJoin('stake_holders', 'stake_holders.id', '=', 'items.stake_holder_id')
                    ->where(['items.deleted_at' => NULL, 'items.published' => 1])
                    ->select('items.item_code', 'items.barcode', 'items.name as item', 'categories.category', 'units.unit', 'brands.brand', 'items.price', 'items.base_unit', 'items.base_unit_conversion')->distinct()->get();
                break;

            case 'default':
                return DB::table('items')
                    ->join('units', 'units.id', '=', 'items.unit_id')
                    ->join('categories', 'categories.id', '=', 'items.category_id')
                    ->join('brands', 'brands.id', '=', 'items.brand_id')
                    ->join('stocks', 'stocks.item_id', '=', 'items.id')
                    ->leftJoin('stake_holders', 'stake_holders.id', '=', 'items.stake_holder_id')
                    ->where(['items.deleted_at' => NULL, 'items.published' => 1])
                    ->select('items.item_code', 'items.barcode', 'items.name as item', 'categories.category', 'units.unit', 'brands.brand', 'items.base_unit', 'items.base_unit_conversion')->distinct()->get();
                break;

            case 'complete':
                return DB::table('items')
                    ->join('units', 'units.id', '=', 'items.unit_id')
                    ->join('categories', 'categories.id', '=', 'items.category_id')
                    ->join('brands', 'brands.id', '=', 'items.brand_id')
                    ->join('stocks', 'stocks.item_id', '=', 'items.id')
                    ->leftJoin('stake_holders', 'stake_holders.id', '=', 'items.stake_holder_id')
                    ->where(['items.deleted_at' => NULL, 'items.published' => 1])
                    ->select('items.item_code', 'items.barcode', 'items.name as item', 'categories.category', 'units.unit', 'brands.brand', 'items.main_cost', 'items.price', 'items.base_unit', 'items.base_unit_conversion')->distinct()->get();
                break;
        }
    }

    /**
     * Generate barcode string value for new imported item
     */
    public static function generate_barcode_value($length)
    {
        $barcode = '';
        for ($i = 0; $i < $length; $i++) {
            $barcode .= rand(0, 9);
        }
        return $barcode;
    }

    /**
     * $line : row of imported csv
     * @return void
     */
    public static function store_imported_item($line, $barcode, $dept)
    {
        $item = Item::create([
            'item_code' => $line[0],
            'barcode' => $barcode,
            'name' => $line[2],
            'category_id' => self::findOrCreate('category', $line[3]),
            'unit_id' => self::findOrCreate('unit', $line[4]),
            'brand_id' => self::findOrCreate('brand', $line[5]),
            'base_unit' => $line[6],
            'base_unit_conversion' => $line[7],
            'main_cost' => $line[8],
            'price' => $line[9],
            'min_stock' => $line[11],
            'cabinet' => self::isNull($line[14]),
            'stake_holder_id' => self::isNull($line[16]),
            'description' => self::isNull($line[17]),
            'published' => 1
        ]);

        $utama = $dept == 'utama' ? $line[10] : 0;
        $gudang = $dept == 'gudang' ? $line[10] : 0;
        self::create_saldo_awal($item->id, 'utama', $utama);
        self::create_saldo_awal($item->id, 'gudang', $gudang);
    }

    public function import_item()
    {
        $file = request()->file('file');
        $extension = $file->getClientOriginalExtension();

        // save file
        request()->file('file')->storeAs('/product_files', 'import_item_data' . '.' . strtolower($extension));

        $csv_file = fopen(storage_path('app/product_files/import_item_data.csv'), "r");

        $flag = true;
        while (($line = fgetcsv($csv_file)) !== FALSE) {
            if ($flag) {
                $flag = false;
                continue;
            } else {
                $barcode = self::generate_barcode_value(10);
                // kalau field barcode => $line[1] ada isinya, itu artinya gaperlu generate barcode lagi.
                // diasumsikan ini file import hasil export dari app ini, jadi field itemcode sama dengan barcode
                if ($line[1]) {
                    $item = Item::where('barcode', $line[1])->first();

                    if (!$item) {
                        self::store_imported_item($line, $line[1], request('dept'));
                    } else {
                        self::update_saldo_awal($item->id, request('dept'), $line[10]);
                    }
                } else {
                    $item = Item::where('item_code', $line[0])->first();
                    // cek apakah itemcode di db tidak ada, maka buat baru dengan barcode baru
                    if (!$item) { // prevent duplicate item
                        try {
                            self::store_imported_item($line, $barcode, request('dept'));
                        } catch (\Throwable $th) {
                            return response()->json(['status' => false, 'message' => 'error: ' . $th->getMessage()], 400);
                        }
                    } else {
                        try {
                            self::update_saldo_awal($item->id, request('dept'), $line[10]);
                        } catch (\Throwable $th) {
                            return response()->json(['status' => false, 'message' => 'error: ' . $th->getMessage()], 400);
                        }
                    }
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
    public static function findOrCreate($entity, $key)
    {
        if ($entity == 'unit') {
            $unit = Unit::where('unit', $key)->first();
            if ($unit) {
                return $unit->id;
            } else {
                $new_unit = Unit::create(['unit' => $key, 'description' => $key]);
                return $new_unit->id;
            }
        } else if ($entity == 'brand') {
            $brand = Brand::where('brand', $key)->first();
            if ($brand) {
                return $brand->id;
            } else {
                $new_brand = Brand::create(['brand' => $key, 'description' => $key]);
                return $new_brand->id;
            }
        } else {
            $category = Category::where('category', $key)->first();
            if ($category) {
                return $category->id;
            } else {
                $new_category = Category::create(['category' => $key, 'description' => $key]);
                return $new_category->id;
            }
        }
    }

    public static function isNull($var)
    {
        if ($var == '')
            return NULL;
        return $var;
    }

    public static function filter_query()
    {
        return QueryBuilder::for(Item::class)
            ->join('units', 'units.id', '=', 'items.unit_id')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->join('brands', 'brands.id', '=', 'items.brand_id')
            ->where(['deleted_at' => NULL, 'published' => 1])
            ->select('items.id', 'items.name', 'items.barcode', 'items.item_code', 'items.base_unit', 'items.base_unit_conversion', 'items.main_cost', 'items.price', 'units.unit', 'categories.category', 'brands.brand')
            ->orderBy('items.name')
            ->allowedFilters([
                AllowedFilter::partial('items.name'),
                AllowedFilter::partial('items.barcode'),
                AllowedFilter::exact('categories.id'),
            ])
            ->paginate(15)
            ->appends(request()->query());
    }

    public function reset_data_item()
    {
        DB::table('items')->update(['deleted_at' => Carbon::now(), 'barcode' => NULL]);
        return back();
    }
}
