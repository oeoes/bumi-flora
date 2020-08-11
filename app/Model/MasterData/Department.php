<?php

namespace App\Model\MasterData;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['code', 'description'];
}
