<?php

namespace App\Http\Controllers;

use Tjscheuneman\ActivityEvents\ActivityEvent;
use App\Services\Profile\ProfileLogic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Validator;

class ProfileController extends Controller
{
    public function index()
    {

        $admin = User::find(Auth::id());
        $activity = ActivityEvent::getAllFromUser(Auth::id());

        return view('admin.profile.index',
            [
                'admin' => $admin,
                'activity' => $activity
            ]
        );
    }

    public function update(Request $request) {

        $rules = array(
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'user' => 'required|string|exists:users,id|exists:admins,user_id'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $returnData['status'] = 'Failure';
            $returnData['message'] = 'Invalid Entry';
            return json_encode($returnData);
        }

        $user = User::find($request->user);
        if ($user === null) {
            $returnData['status'] = 'Failure';
            $returnData['message'] = 'Invalid Entry';
            return json_encode($returnData);
        }

        ProfileLogic::update($user, $request->first_name, $request->last_name);

        $returnData['status'] = 'Success';
        $returnData['message'] = 'Profile has been updated';
        return json_encode($returnData);
    }

    public function updateUser(Request $request) {

        $rules = array(
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'user' => 'required|string|exists:users,id'
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $returnData['status'] = 'Failure';
            $returnData['message'] = 'Invalid Entry';
            return json_encode($returnData);
        }

        $user = User::find($request->user);
        if ($user === null) {
            $returnData['status'] = 'Failure';
            $returnData['message'] = 'Invalid Entry';
            return json_encode($returnData);
        }

        ProfileLogic::update($user, $request->first_name, $request->last_name);

        $returnData['status'] = 'Success';
        $returnData['message'] = 'Profile has been updated';
        return json_encode($returnData);
    }
}
