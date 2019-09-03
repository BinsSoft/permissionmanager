<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolePermissions extends Model
{
    protected $table = "role_permissions";
    public $timestamps = false;
    public function navigation() {
        return $this->belongsTo('\App\Navigations','nav_id');
    }
    public function role() {
        return $this->belongsTo('\App\Roles','role_id');
    }
}
