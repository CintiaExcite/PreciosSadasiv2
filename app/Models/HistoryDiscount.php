<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryDiscount extends Model
{
    public function discount()
	{
		return $this->belongsTo(Discount::class);
	}
}
