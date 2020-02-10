<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];

	/**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at', 'created_at'
    ];

	public function developments()
	{
		return $this->hasMany(Development::class);
	}
}
