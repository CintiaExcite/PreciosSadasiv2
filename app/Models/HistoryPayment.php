<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryPayment extends Model
{
    public function payment()
	{
		return $this->belongsTo(Payment::class);
	}
}
