<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'price', 'text_before_price', 'text_after_price',
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

    public function history_prices()
    {
        return $this->hasMany(HistoryPrice::class);
    }

    /*public function getPriceAttribute($value) {
        if ($value == 0.00) return "Por definir";
        return $value;
    }*/
}
