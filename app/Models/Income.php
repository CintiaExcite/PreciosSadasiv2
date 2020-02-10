<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'income_from'
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

    public function history_incomes()
    {
        return $this->hasMany(HistoryIncome::class);
    }

    /*public function getIncomeFromAttribute($value) {
        if ($value == 0.00) return "Por definir";
        return $value;
    }*/
}
