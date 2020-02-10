<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Specifications extends Model
{
    use SoftDeletes;
    protected $table = 'specifications';
    protected $fillable = ['description'];
    protected $dates = ['deleted_at'];
}
