<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;

class SpecificationEntry extends Model
{
    use Uuids;

    public $incrementing = false;

    public function spec()
    {
        return $this->hasOne('App\Specification', 'id','specification_id');
    }
}
