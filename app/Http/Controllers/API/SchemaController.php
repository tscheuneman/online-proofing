<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\Specification\SpecificationSchemaLogic;

class SchemaController extends Controller
{
    public function getInfo($id) {
        return SpecificationSchemaLogic::getSpecData($id);
    }
}
