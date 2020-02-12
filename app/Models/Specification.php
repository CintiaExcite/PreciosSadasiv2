<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Specification extends Model
{
    use SoftDeletes;
    protected $table = 'specifications';
    protected $fillable = ['id','description'];
    protected $dates = ['deleted_at'];

    public function products()
    {
        return $this->belongsToMany(Product::class,'prod_level_spec')->withPivot('level_id','quantity');
    }

    public function levels()
    {
        return $this->belongsToMany(Level::class,'prod_level_spec')->withPivot('product_id','quantity');
    }

}
