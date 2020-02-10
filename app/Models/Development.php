<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Development extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];

	/**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'salesup_tklinea', 'deleted_at', 'created_at'
    ];

	public function state()
	{
		return $this->belongsTo(State::class);
	}

	public function products()
	{
		return $this->hasMany(Product::class);
	}

	public function tokens()
	{
		return $this->hasOne(Token::class);
	}

	public function development_info()
	{
		return $this->hasOne(DevelopmentInfo::class);
	}

	public function getImageSysAttribute($value) {
		if ($value != null && $value != '') {
			return "http://sadasi.test/precios/public/files/" . $value;
		} else { return $value; }
    }
}
