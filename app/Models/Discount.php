<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'discount'
    ];

	/**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at'
    ];
	
    public function product()
	{
		return $this->belongsTo(Product::class);
	}

    public function history_discounts()
    {
        return $this->hasMany(HistoryDiscount::class);
    }

    /*public function getDiscountAttribute($value) {
        if ($value == 0.00) return "Por definir";
        return $value;
    }*/
}
