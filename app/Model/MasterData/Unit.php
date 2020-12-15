<?php

namespace App\Model\MasterData;

use Illuminate\Database\Eloquent\Model;
use App\Model\MasterData\Item;

class Unit extends Model
{
    protected $fillable = ['unit', 'description'];

    public function item () {
        return $this->hasMany(Item::class);
    }
}
