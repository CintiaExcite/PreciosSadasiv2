<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payment_from', 'show'
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

    public function history_payments()
    {
        return $this->hasMany(HistoryPayment::class);
    }

    /*public function getPaymentsFromAttribute($value) {
        if ($value == 0.00) return "Por definir";
        return $value;
    }*/
}
