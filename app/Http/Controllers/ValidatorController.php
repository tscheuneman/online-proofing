<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;

use App\Services\Validation\ValidateLogic;

class ValidatorController extends Controller
{

    public function spec(Request $request)
    {
        $rules = array(
            'id' => 'required|exists:specifications,id',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $returnData['status'] = 'Failure';
            $returnData['message'] = 'Invalid Entry';
            return json_encode($returnData);
        }

        if(ValidateLogic::checkSpecification($request)) {
            $returnData['status'] = 'Success';
            $returnData['message'] = 'Valid Entry';
            return json_encode($returnData);
        }

        return 'bye';
    }
}
