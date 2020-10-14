<?php

namespace App\Model\Storage;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;

class ItemIn extends Model
{
    use Uuids;

    public $incrementing = False;

    protected $guarded = [''];
}
