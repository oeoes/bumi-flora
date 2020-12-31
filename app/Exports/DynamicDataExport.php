<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;

class DynamicDataExport implements FromCollection, WithHeadings
{
    private $reportType, $heading, $query;

    public function __construct($reportType)
    {
        $this->reportType = $reportType;
        $this->generate();
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return (array)$this->heading;
    }

    public function generate()
    {
        switch ($this->reportType) {
            case 'main_cost':
                $this->query = DB::table('items')
                    ->join('units', 'units.id', '=', 'items.unit_id')
                    ->join('categories', 'categories.id', '=', 'items.category_id')
                    ->join('brands', 'brands.id', '=', 'items.brand_id')
                    ->join('stocks', 'stocks.item_id', '=', 'items.id')
                    ->leftJoin('stake_holders', 'stake_holders.id', '=', 'items.stake_holder_id')
                    ->where(['items.deleted_at' => NULL, 'items.published' => 1])
                    ->select('items.barcode as item_code', 'items.barcode', 'items.name', 'categories.category', 'units.unit', 'brands.brand', 'items.main_cost', 'items.base_unit', 'items.base_unit_conversion')->distinct()->get();
                $heading = ["Kode Item", "Barcode", "Nama Item", "Jenis", "Satuan", "Merk", "Harga pokok", "Satuan Dasar", "Konversi satuan dasar"];
                $this->heading = $heading;
                break;

            case 'price':
                $this->query = DB::table('items')
                    ->join('units', 'units.id', '=', 'items.unit_id')
                    ->join('categories', 'categories.id', '=', 'items.category_id')
                    ->join('brands', 'brands.id', '=', 'items.brand_id')
                    ->join('stocks', 'stocks.item_id', '=', 'items.id')
                    ->leftJoin('stake_holders', 'stake_holders.id', '=', 'items.stake_holder_id')
                    ->where(['items.deleted_at' => NULL, 'items.published' => 1])
                    ->select('items.barcode as item_code', 'items.barcode', 'items.name as item', 'categories.category', 'units.unit', 'brands.brand', 'items.price', 'items.base_unit', 'items.base_unit_conversion')->distinct()->get();
                $heading = ["Kode Item", "Barcode", "Nama Item", "Jenis", "Satuan", "Merk", "Harga jual", "Satuan Dasar", "Konversi satuan dasar"];
                $this->heading = $heading;
                break;

            case 'default':
                $this->query = DB::table('items')
                    ->join('units', 'units.id', '=', 'items.unit_id')
                    ->join('categories', 'categories.id', '=', 'items.category_id')
                    ->join('brands', 'brands.id', '=', 'items.brand_id')
                    ->join('stocks', 'stocks.item_id', '=', 'items.id')
                    ->leftJoin('stake_holders', 'stake_holders.id', '=', 'items.stake_holder_id')
                    ->where(['items.deleted_at' => NULL, 'items.published' => 1])
                    ->select('items.barcode as item_code', 'items.barcode', 'items.name as item', 'categories.category', 'units.unit', 'brands.brand', 'items.base_unit', 'items.base_unit_conversion')->distinct()->get();
                $heading = ["Kode Item", "Barcode", "Nama Item", "Jenis", "Satuan", "Merk", "Satuan Dasar", "Konversi satuan dasar"];
                $this->heading = $heading;
                break;

            case 'complete':
                $this->query = DB::table('items')
                    ->join('units', 'units.id', '=', 'items.unit_id')
                    ->join('categories', 'categories.id', '=', 'items.category_id')
                    ->join('brands', 'brands.id', '=', 'items.brand_id')
                    ->join('stocks', 'stocks.item_id', '=', 'items.id')
                    ->leftJoin('stake_holders', 'stake_holders.id', '=', 'items.stake_holder_id')
                    ->where(['items.deleted_at' => NULL, 'items.published' => 1])
                    ->select('items.barcode as item_code', 'items.barcode', 'items.name as item', 'categories.category', 'units.unit', 'brands.brand', 'items.main_cost', 'items.price', 'items.base_unit', 'items.base_unit_conversion')->distinct()->get();
                $heading = ["Kode Item", "Barcode", "Nama Item", "Jenis", "Satuan", "Merk", "Harga pokok", "Harga jual", "Satuan Dasar", "Konversi satuan dasar"];
                $this->heading = $heading;
                break;
        }
    }
}
