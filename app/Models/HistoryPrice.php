<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryPrice extends Model
{
    public function price()
	{
		return $this->belongsTo(Price::class);
	}
}
