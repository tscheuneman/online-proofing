<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;

class Order extends Model
{
    use Uuids;

    public $incrementing = false;

    public function projects()
    {
        return $this->hasMany('App\Project', 'ord_id','id');
    }
    public function admins()
    {
        return $this->hasMany('App\AdminAssign', 'order_id','id')->select('id', 'user_id', 'order_id');
    }
    public function category()
    {
        return $this->hasOne('App\Categories', 'id','cat_id')->select('id', 'name');
    }
    public function users()
    {
        return $this->hasMany('App\UserAssign', 'order_id','id')->select('id', 'user_id', 'order_id');
    }
}
