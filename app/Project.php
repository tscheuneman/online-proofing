<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;

class Project extends Model
{
    use Uuids;

    public $incrementing = false;

    public function admin_entries() {
        return $this->hasMany('App\Entry', 'project_id','id')->select('id', 'project_id', 'admin', 'active', 'created_at')->latest();
    }
    public function entries() {
        return $this->hasMany('App\Entry', 'project_id','id')->where('active', '=', true)->latest();
    }
    public function order()
    {
        return $this->belongsTo('App\Order', 'ord_id', 'id');
    }
}
