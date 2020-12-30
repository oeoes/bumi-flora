<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;

class MasterDataExport implements FromCollection, WithHeadings
{
    protected $dept;
    public function __construct($dept) {
        $this->dept = $dept;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DB::table('items')
            ->join('units', 'units.id', '=', 'items.unit_id')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->join('brands', 'brands.id', '=', 'items.brand_id')
            ->join('stocks', 'stocks.item_id', '=', 'items.id')
            ->leftJoin('stake_holders', 'stake_holders.id', '=', 'items.stake_holder_id')
            ->where('stocks.dept', $this->dept)
            ->select('items.barcode as item_code', 'items.barcode', 'items.name as item', 'categories.category', 'units.unit', 'brands.brand', 'items.base_unit', 'items.base_unit_conversion', 'items.main_cost', 'items.price', DB::raw('IFNULL(stocks.amount, 0) as stock'), 'items.min_stock', 'categories.id as item_type', 'items.base_unit_conversion as using_serial', 'items.cabinet', 'stocks.dept', 'stake_holders.name', 'items.description')
            ->get();
    }

    public function headings(): array
    {
        return ["Kode Item", "Barcode", "Nama Item", "Jenis", "Satuan", "Merk", "Satuan Dasar", "Konversi satuan dasar", "Harga pokok", "Harga jual", "Stok", "Stok Min.", "Tipe Item", "Menggunakan Serial",  "Rak", "Kode Gudang - Kantor", "Supplier", "Keterangan"];
    }
}
