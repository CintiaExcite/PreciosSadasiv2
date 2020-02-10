<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryIncome extends Model
{
    public function income()
	{
		return $this->belongsTo(Income::class);
	}
}
