<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;

class SpecificationSchema extends Model
{
    use Uuids;

    public $incrementing = false;

    public function specs()
    {
        return $this->hasMany('App\SpecificationEntry', 'schema_id','id');
    }
}
