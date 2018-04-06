<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;

class Project extends Model
{
    use Uuids;

    public $incrementing = false;

    public function category()
    {
        return $this->hasOne('App\Categories', 'id','cat_id')->select('id', 'name');
    }
    public function users()
    {
        return $this->hasMany('App\UserAssign', 'project_id','id')->select('id', 'user_id', 'project_id');
    }
    public function admins()
    {
        return $this->hasMany('App\AdminAssign', 'project_id','id')->select('id', 'user_id', 'project_id');
    }
    public function admin_entries() {
        return $this->hasMany('App\Entry', 'project_id','id')->select('id', 'project_id', 'admin', 'active')->where('active', '=', true)->latest();
    }
    public function entries() {
        return $this->hasMany('App\Entry', 'project_id','id')->where('active', '=', true)->latest();
    }
}
