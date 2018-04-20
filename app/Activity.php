<?php

namespace App;

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
}
