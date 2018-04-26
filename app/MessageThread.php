<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;

class MessageThread extends Model
{
    use Uuids;

    public $incrementing = false;

    public function msg_cnt()
    {
        return $this->hasMany('App\Message', 'thread_id','id')->select('id', 'thread_id');
    }
}
