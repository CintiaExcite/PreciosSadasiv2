<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
    use SoftDeletes;
    protected $table = 'level';
    protected $fillable = ['id','level'];
    protected $dates = ['deleted_at'];


    public function products()
    {
        return $this->belongsToMany(Product::class,'prod_level_spec')->withPivot('specification_id','quantity');
    }

    public function specification()
    {
        return $this->belongsToMany(Specification::class,'prod_level_spec')->withPivot('product_id','quantity');
    }



}