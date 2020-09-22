<?php

namespace App\Model\Relation;

use Illuminate\Database\Eloquent\Model;

class StakeHolder extends Model
{
    // protected $fillable = ['code', 'name', 'address', 'country', 'province', 'city', 'postal_code', 'phone', 'email', 'card_number', 'owner', 'bank', 'type'];
    protected $guarded = ['created_at', 'updated_at'];
}
