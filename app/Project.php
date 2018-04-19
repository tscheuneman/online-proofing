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
            'projects.project_name' => 10,
            'orders.job_id' => 8,
            'texts.data' => 7,
        ],
        'joins' => [
            'orders' => ['orders.id', 'projects.ord_id'],
            'texts' => ['texts.project_id', 'projects.id'],
        ]
    ];

    public $incrementing = false;

    public function admin_entries() {
        return $this->hasMany('App\Entry', 'project_id','id')->select('id', 'project_id', 'admin', 'active', 'created_at')->latest();
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


