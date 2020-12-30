<?php

namespace App\Model\Activity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;

class Transaction extends Model
{
    use Uuids, SoftDeletes;

    public $incrementing = False;

    protected $guarded = [''];
}