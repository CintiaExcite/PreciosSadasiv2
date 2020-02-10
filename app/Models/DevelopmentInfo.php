<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DevelopmentInfo extends Model
{
    use SoftDeletes;
    protected $table = 'development_info';
    protected $fillable = ['development_id','location','phone','email'];
    protected $dates = ['deleted_at'];

    protected $hidden = [
        'deleted_at', 'created_at'
    ];

    public function development()
    {
        return $this->belongsTo(Development::class);
    }
}
