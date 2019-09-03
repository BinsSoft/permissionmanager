<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    protected $table = "user_roles";
    public $timestamps = false;
    public function role() {
        return $this->belongsTo('\App\Roles','role_id');
    }
    public function user() {
        return $this->belongsTo('\App\User', 'id' ,'user_id');
    }
}
