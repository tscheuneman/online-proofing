<?php

namespace App\Http\Controllers;

use App\Message;
use App\Services\Specification\SpecificationSchemaLogic;
use Illuminate\Http\Request;

use App\Services\Project\ProjectLogic;
use App\Services\Message\MessageLogic;

use Auth;

use Validator;
use Redirect;


class ProjectActionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }






}
