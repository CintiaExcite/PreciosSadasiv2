<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Amenity extends Model
{
    use SoftDeletes;
    protected $table = 'amenities';
    protected $fillable = ['description'];
    protected $dates = ['deleted_at'];

    /*Funcion Development*/
	public function development()
	{
    	return $this->belongsToMany(Development::class, 'developments_amenities')->withPivot('amenity_id');
	}
}
