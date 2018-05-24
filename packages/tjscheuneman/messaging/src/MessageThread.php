<?php

namespace Tjscheuneman\Messaging;

use Illuminate\Database\Eloquent\Model;

use Emadadly\LaravelUuid\Uuids;

class MessageThread extends Model
{
    use Uuids;

    public $incrementing = false;

    public function msg_cnt()
    {
        return $this->hasMany('Tjscheuneman\Messaging\Message', 'thread_id','id')->select('id', 'thread_id');
    }
}
