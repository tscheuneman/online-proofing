<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Emadadly\LaravelUuid\Uuids;
use Nicolaslopezj\Searchable\SearchableTrait;

class Project extends Model
{
    use Uuids;

    use SearchableTrait;

    protected $searchable = [
        'columns' => [
            'projects.project_name' => 40,
            'orders.job_id' => 40,
            'texts.data' => 70,
            'entries.notes' => 70,
            'users.first_name' => 15,
            'users.last_name' => 15,
        ],
        'joins' => [
            'orders' => ['orders.id', 'projects.ord_id'],
            'texts' => ['texts.project_id', 'projects.id'],
            'entries' => ['entries.project_id', 'projects.id'],
            'user_assigns' => ['user_assigns.order_id', 'projects.ord_id'],
            'users' => ['user_assigns.user_id', 'users.id'],
        ]
    ];

    public $incrementing = false;

    public function admin_entries() {
        return $this->hasMany('App\Entry', 'project_id','id')->select('id', 'project_id', 'admin', 'active', 'created_at')->latest();
    }

    public function inital_image() {
        return $this->hasMany('App\Entry', 'project_id','id')->select('id', 'path', 'project_id', 'files')->oldest();
    }

    public function entries() {
        return $this->hasMany('App\Entry', 'project_id','id')->where('active',true)->latest();
    }
    public function order()
    {
        return $this->belongsTo('App\Order', 'ord_id', 'id');
    }

    public function entryInfo() {
        return $this->hasMany('App\Entry', 'project_id','id')->select('id', 'project_id', 'admin', 'active', 'created_at', 'pdf_path')->latest();
    }

    public function order_name() {
        return $this->belongsTo('App\Order', 'ord_id', 'id')->select('id', 'job_id');
    }
}


