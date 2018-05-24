<?php

namespace Tjscheuneman\Messaging;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;

class Message extends Model
{
    use Uuids;

    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id')->select('id', 'first_name', 'last_name', 'picture');
    }
}
