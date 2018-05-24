<?php

namespace Tjscheuneman\Proofing;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;

class Entry extends Model
{
    use Uuids;

    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id')->select('id', 'first_name', 'last_name', 'picture');
    }

    public function project()
    {
        return $this->belongsTo('App\Project', 'project_id', 'id')->select('id', 'project_name');
    }
}
