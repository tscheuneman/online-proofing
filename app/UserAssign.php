<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;

class UserAssign extends Model
{
    use Uuids;

    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id')->select('id', 'first_name', 'last_name', 'email', 'picture');
    }

    public function search_user() {
        return $this->belongsTo('App\User', 'user_id')->select('id', 'first_name', 'last_name', 'picture', 'org');
    }

    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id', 'id');
    }
}
