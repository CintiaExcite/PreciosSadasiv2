<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdLevelSpecific extends Model
{
    protected $table = 'prod_level_spec';
    protected $fillable = ['product_id','level_id','specification_id','quantity'];
    protected $dates = ['deleted_at'];
}
