<?php

namespace App\Model\Activity;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;

class Transaction extends Model
{
    use Uuids;

    public $incrementing = False;

    protected $guarded = [''];
}