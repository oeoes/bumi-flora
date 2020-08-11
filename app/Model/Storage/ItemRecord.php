<?php

namespace App\Model\Storage;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;

class ItemRecord extends Model
{
    use Uuids;

    public $incrementing = False;
    
    protected $fillable = ['item_id', 'dept', 'transaction_no', 'type', 'date', 'description', 'amount'];
}
