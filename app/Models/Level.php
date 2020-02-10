<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
    use SoftDeletes;
    protected $table = 'level';
    protected $fillable = ['level'];
    protected $dates = ['deleted_at'];
}
