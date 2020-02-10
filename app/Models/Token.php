<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Token extends Model
{
    use SoftDeletes;
    protected $table = 'tokens';
    protected $fillable = ['development_id','cf_salesup','cf_salesup_tk_medio','cf_salesup_tk_cuenta','cf_salesup_tk_desarrollo','cf_salesup_id_estado','cf_salesup_tk_region','cf_salesup_tk_campania'];
    protected $dates = ['deleted_at'];

    protected $hidden = [
        'deleted_at', 'created_at'
    ];

    public function development()
    {
        return $this->belongsTo(Development::class);
    }

}
