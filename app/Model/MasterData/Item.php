<?php

namespace App\Model\MasterData;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;

class Item extends Model
{
    use Uuids;

    public $incrementing = False;

    protected $fillable = ['item_type_id', 'category_id', 'stake_holder_id', 'unit_id', 'brand_id', 'name', 'image', 'code', 'type', 'cabinet', 'sale_status', 'description', 'main_cost', 'barcode', 'price', 'min_stock'];
}
