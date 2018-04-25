<?php

namespace App\Http\Controllers;

class UserSideParentController extends Controller
{

    protected $val;
    public function __construct()
    {
        $this->middleware('user');
        $this->val = 50;
    }
}
