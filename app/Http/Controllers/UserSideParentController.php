<?php

namespace App\Http\Controllers;

use App\Services\Project\UserProjectLogic;
use Illuminate\Support\Facades\Auth;

class UserSideParentController extends Controller
{

    protected $val;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->val = UserProjectLogic::getUserActionRequired(Auth::id());
            return $next($request);
        });
    }
}
