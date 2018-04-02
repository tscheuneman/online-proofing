<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;

class Admin extends Model
{
    use Uuids;

    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
