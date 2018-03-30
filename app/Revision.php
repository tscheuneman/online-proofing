<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;

class Revision extends Model
{
    use Uuids;

    public $incrementing = false;
}
