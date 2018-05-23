<?php

namespace Tjscheuneman\Quoting;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;

class Quote extends Model
{
    use Uuids;

    public $incrementing = false;
}

