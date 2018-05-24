<?php

namespace Tjscheuneman\Tracking;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;

class SubStation extends Model
{
    use Uuids;

    public $incrementing = false;
}