<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

	protected $dates = ['deleted_at'];

	/**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'salesup_tkproducto', 'deleted_at', 'created_at'
    ];

	public function development()
	{
		return $this->belongsTo(Development::class);
	}

	public function price()
	{
		return $this->hasOne(Price::class);
	}

	public function discount()
	{
		return $this->hasOne(Discount::class);
	}

	public function income()
	{
		return $this->hasOne(Income::class);
	}

	public function payment()
	{
		return $this->hasOne(Payment::class);
	}

	public function impressions()
	{
		return $this->hasMany(Impression::class);
	}

	public function getImageSysAttribute($value) {
		if ($value != null && $value != '') {
			return "https://precios.sadasi.com/v2/files/" . $value;
		} else { return $value; }
    }
}
