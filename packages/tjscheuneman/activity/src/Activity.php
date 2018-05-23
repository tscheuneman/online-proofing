<?php

namespace Tjscheuneman\ActivityEvents;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;

class Activity extends Model
{
    use Uuids;
    public $incrementing = false;

    public function project()
    {
        return $this->belongsTo('App\Project', 'project_id')->select('id', 'project_name', 'ord_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id')->select('id', 'first_name', 'last_name');
    }
}
