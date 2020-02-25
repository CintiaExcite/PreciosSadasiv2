<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AmenitiesDevelopment extends Model
{
    use SoftDeletes;
protected $hidden = 'developments_amenities';

protected $hidden = ['development_id', 'amenity_id',];
//protected $dates = ['deleted_at'];


}


