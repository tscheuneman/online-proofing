<?php

namespace Tjscheuneman\Proofing;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;

class Text extends Model
{
    use Uuids;

    public $incrementing = false;
}
