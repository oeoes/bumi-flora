<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;

class MasterDataExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DB::table('items')
            ->join('units', 'units.id', '=', 'items.unit_id')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->join('brands', 'brands.id', '=', 'items.brand_id')
            ->leftJoin('stake_holders', 'stake_holders.id', '=', 'items.stake_holder_id')
            ->select('items.barcode', 'items.name as item', 'categories.category', 'units.unit', 'brands.brand', 'items.base_unit', 'items.base_unit_conversion', 'items.main_cost', 'items.price', 'items.min_stock', 'items.cabinet', 'stake_holders.name', 'items.description')
            ->get();
    }

    public function headings(): array
    {
        return ["Barcode", "Item", "Jenis", "Satuan", "Merk", "Satuan Dasar", "Konversi satuan dasar", "Harga asli", "Harga jual", "Stok Min.", "Rak", "Supplier", "Keterangan"];
    }
}
