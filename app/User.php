<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
 
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Get permitions
     */
    public static function permitsArray()
    {
        return explode(',', request()->user()->permits);
    }

    public static function permitsEArray()
    {
        return explode(',', request()->user()->permits_e);
    }

    public static function searchPermitOnArray($search)
    {
        $permits = self::permitsArray();
        //$search is Array
        if (!is_array($search)) {
            return in_array($search, $permits);
        }
        //$search isn´t Array
        $valid = 0;
        foreach ($search as $key => $value) {
            if(in_array($value, $permits)) $valid++;
        }
        return ($valid > 0) ? true : false;
    }

    public static function searchPermitEOnArray($search)
    {
        $permits_e = self::permitsEArray();
        if (count($permits_e) == 0) {
            return false;
        }
        //$search is Array
        if (!is_array($search)) {
            return in_array($search, $permits_e);
        }
        //$search isn´t Array
        $valid = 0;
        foreach ($search as $key => $value) {
            if(in_array($value, $permits_e)) $valid++;
        }
        return ($valid > 0) ? true : false;
    }
}
