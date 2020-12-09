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
                ->select('items.name', 'items.barcode', 'units.unit', 'categories.category','items.cabinet', 'items.main_cost', 'items.price', 'items.base_unit', 'items.base_unit_conversion')
                ->get();
    }

    public function headings(): array
    {
        return ["Item", "Barcode", "Satuan", "Jenis", "Rak", "Harga asli", "Harga jual", "Satuan Dasar", "Konversi satuan dasar"];
    }
}
