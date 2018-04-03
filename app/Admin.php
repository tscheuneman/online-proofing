<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;
use Nicolaslopezj\Searchable\SearchableTrait;


class Admin extends Model
{
    use Uuids;
    use SearchableTrait;

    public $incrementing = false;
    protected $searchable = [
        'columns' => [
            'users.first_name' => 10,
            'users.last_name' => 10,
            'users.email' => 8,
        ],
        'joins' => [
            'users' => ['users.id','admins.user_id'],
        ],
    ];


    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function userSearch()
    {
        return $this->belongsTo('App\User', 'user_id')->select('id', 'first_name', 'last_name');
    }
}
