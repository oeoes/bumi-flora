<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;

class StockOpnameExport implements FromCollection, WithHeadings
{
    use Exportable;


    private $request;
    public function __construct($req)
    {
        $this->request = $req;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = DB::table('items')
            ->join('units', 'units.id', '=', 'items.unit_id')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->join('balances', 'items.id', '=', 'balances.item_id')
            ->join('stocks', 'items.id', '=', 'stocks.item_id')
            ->leftJoin('storage_records', 'items.id', '=', 'storage_records.item_id')
            ->select('items.name', 'items.barcode', 'units.unit', 'categories.category', 'balances.dept', 'items.cabinet', 'balances.amount as balance', DB::raw('IFNULL(sum(storage_records.amount_in), 0) as amount_in'), DB::raw('IFNULL(sum(storage_records.amount_out), 0) as amount_out'), DB::raw('((IFNULL(sum(storage_records.amount_in), 0) + balances.amount) - IFNULL(sum(storage_records.amount_out), 0)) as buku'), DB::raw('((IFNULL(sum(storage_records.amount_in), 0) + balances.amount) - IFNULL(sum(storage_records.amount_out), 0)) as fisik'), DB::raw('((IFNULL(sum(storage_records.amount_in), 0) + balances.amount) - IFNULL(sum(storage_records.amount_out), 0)) as selisih'))
            ->where(['items.cabinet' => !$this->request->cabinet ? NULL : $this->request->cabinet, 'categories.id' => $this->request->category, 'storage_records.dept' => $this->request->dept, 'stocks.dept' => $this->request->dept, 'balances.dept' => $this->request->dept, 'items.deleted_at' => NULL])
            ->whereBetween('items.created_at', [$this->request->from, $this->request->to])
            ->groupBy('items.id')
            ->get();

        foreach ($data as $key => $dt) {
            $selisih = $this->request->data_input[$key] - $dt->buku;
            $dt->fisik = $this->request->data_input[$key];
            $dt->selisih = !$selisih ? '0' : $selisih;
        }
        return $data;
    }

    public function headings(): array
    {
        return ["Item", "Barcode", "Satuan", "Jenis", "Dept", "Rak", "Awal", 'Masuk', 'Keluar', 'Buku', 'Fisik', 'Selisih'];
    }
}
