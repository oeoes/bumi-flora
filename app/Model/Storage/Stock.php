<?php

namespace App\Model\Storage;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;

class Stock extends Model
{
    use Uuids;

    public $incrementing = False;
    protected $fillable = ['item_id', 'dept', 'amount'];
}
