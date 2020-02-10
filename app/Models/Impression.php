<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Impression extends Model
{
	public function product()
	{
		return $this->belongsTo(Product::class);
	}

	public static function views($product_id, $origin)
	{
		$search_impression = Impression::where([['product_id', $product_id], ['origin', $origin]])->whereDate('date', date('Y-m-d'))->first();
		if ($search_impression != null) {
			$search_impression->views = $search_impression->views + 1;
			$search_impression->save();
		} else {
			$impression = new Impression;
			$impression->product_id = $product_id;
			$impression->views = 1;
			$impression->date = date('Y-m-d');
			$impression->origin = $origin;
			$impression->save();
		}
		return true;
	}
}
