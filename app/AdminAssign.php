<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;

class AdminAssign extends Model
{
    use Uuids;

    public $incrementing = false;

    public function admin()
    {
        return $this->belongsTo('App\Admin', 'user_id', 'id')->select('id', 'user_id');
    }
}
