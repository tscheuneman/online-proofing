<?php

namespace App\Services\Validation;

use App\Specification;
use App\User;
use Illuminate\Http\Request;
use Validator;


class ValidateLogic {

    public static function checkSpecification(Request $request) {
        $spec = Specification::findOrFail($request->id);
            $type = $spec->type;

            switch($type) {
                case "number":
                    $rules = array(
                        'id' => 'required|numeric',
                    );
                    break;
                case "textarea":
                    $rules = array(
                        'id' => 'required|string',
                    );
                    break;
                default:
                    $rules = array(
                        'id' => 'required|string',
                    );
            }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return false;
        }
        return true;

    }

}

?>