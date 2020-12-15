<?php

namespace App\Model\MasterData;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\MasterData\Unit;

class Item extends Model
{
    use Uuids, SoftDeletes;

    public $incrementing = False;

    protected $guarded = [''];

    public function unit () {
        return $this->belongsTo(Unit::class);
    }
}
